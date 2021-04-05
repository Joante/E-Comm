<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());
        }

        if($request->has('status'))
        {
            if($request->get('status') == 'active')
            {
                $status = 1;
            }
            else if($request->get('status') == 'inactive')
            {
                $status = 0;
            }
        }
        if($request->has('page'))
        {
            if($request->has('status') && $request->has('limit'))
            {
                $results = DB::table('articles')->where('status', '=', $status)->paginate($request->get('limit'),['*'],'page', $request->get('page'));
            }
            else if($request->has('status') && !$request->has('limit'))
            {
                $results = DB::table('articles')->where('status', '=', $status)->paginate(15,['*'],'page',$request->get('page'));
            }
            else if(!$request->has('status') && $request->has('limit'))
            {
                $results = DB::table('articles')->paginate($request->get('limit'),['*'],'page', $request->get('page'));
            }
            else if(!$request->has('status') && !$request->has('limit'))
            {
                $results = DB::table('articles')->paginate(15,['*'],'page',$request->get('page'));
            }
        }else
        {
            if($request->has('status') && $request->has('limit'))
            {
                $results = DB::table('articles')->where('status', '=', $status)->paginate($request->get('limit'));
            }
            else if($request->has('status') && !$request->has('limit'))
            {
                $results = DB::table('articles')->where('status', '=', $status)->paginate();
            }
            else if(!$request->has('status') && $request->has('limit'))
            {
                $results = DB::table('articles')->paginate($request->get('limit'));
            }
            else if(!$request->has('status') && !$request->has('limit'))
            {
                $results = DB::table('articles')->paginate(15);
            }
        }
        $response['page'] = $results->currentPage();
        $response['limit'] = $results->perPage();
        $response['results'] = $results->items();
        $response['total'] = $results->total();//->count()
        return json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.1',
            'status' => 'required|in:active,inactive',
            'qty' => 'required|integer|min:1|max:999999'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());
        }

        if($request->get('status') == 'active')
        {
            $status = true;
        }else
        {
            $status = false;
        }
        $article = new Article;

        $article->title = $request->get('title');
        $article->price = $request->get('price');
        $article->status = $status;
        $article->qty = $request->get('qty');

        if(!$article->save())
        {
            $response['message'] = "Error al guardar el articulo en la base de datos.";
            return json_encode($response);
        }

        $response['message'] = "Articulo guardado exitosamente.";
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        if($article == null)
        {
            $response['message'] = "Articulo no encontrado.";
            return json_encode($response);
        }

        return json_encode($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if($article == null)
        {
            $response['message'] = "Articulo no encontrado.";
            return json_encode($response);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0.1',
            'status' => 'nullable|in:active,inactive',
            'qty' => 'nullable|integer|min:1|max:999999'
        ]);

        if ($validator->fails()) {
            return json_encode($validator->errors());
        }

        if($request->has('status'))
        {
            if($request->get('status') == 'active')
            {
                $request->merge([
                    'status' => true,
                ]);
            }else
            {
                $request->merge([
                    'status' => false,
                ]);
            }
        }


        if(!$article->update($request->all()))
        {
            $response['message'] = "Error al actualizar el articulo en la base de datos.";
            return json_encode($response);
        }

        $response['message'] = "Articulo actualizado exitosamente.";
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        if($article == null)
        {
            $response['message'] = "Articulo no encontrado.";
            return json_encode($response);
        }

        if(!$article->delete())
        {
            $response['message'] = "Error al borrar el articulo en la base de datos.";
            return json_encode($response);
        }

        $response['message'] = "Articulo borrado exitosamente.";
        return json_encode($response);
    }

    public function listByStatus($status)
    {
        if($status == 'active')
        {
            $response['Count'] = DB::table('articles')->where('status', '=', true)->count();
        }else if($status == 'inactive')
        {
            $response['Count'] = DB::table('articles')->where('status', '=', false)->count();
        }else
        {
            $response['message'] = "Error. El status tiene que ser active o inactive.";
        }

        return json_encode($response);
    }
}
