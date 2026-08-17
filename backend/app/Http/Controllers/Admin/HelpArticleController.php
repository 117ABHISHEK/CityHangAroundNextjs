<?php
namespace App\Http\Controllers\Admin;

use App\Models\HelpArticle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpArticleController extends Controller
{
    public function index()
    {
        $page_data['articles'] = HelpArticle::latest()->paginate(50);
        $page_data['view_path'] = 'help_articles.index';
        return view('backend.index', $page_data);
        //return view('admin.help_articles.index', compact('articles'));
    }

    public function create()
    {
        $page_data['view_path'] = 'help_articles.create';
        return view('backend.index', $page_data);
        //return view('admin.help_articles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'content' => 'required']);

        HelpArticle::create($request->only('title', 'content'));
        return redirect()->route('admin.help-articles.index')->with('success', 'Article created!');
    }

    public function edit($id)
    {
        $helpArticle = HelpArticle::findOrFail($id);
        $page_data['article'] = $helpArticle;
        $page_data['view_path'] = 'help_articles.edit';
        return view('backend.index', $page_data);
        //return view('admin.help_articles.edit', compact('helpArticle'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
    
        $helpArticle = HelpArticle::findOrFail($id);
    
        $helpArticle->update($request->only('title', 'content'));
    
        return redirect()->route('admin.help-articles.index')->with('success', 'Article updated!');
    }
    

    public function destroy($id)
    {
        $helpArticle = HelpArticle::findOrFail($id);
        $helpArticle->delete();

        return back()->with('success', 'Deleted!');
    }

}
