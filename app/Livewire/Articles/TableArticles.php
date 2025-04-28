<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TableArticles extends Component
{
    use WithPagination;

    public $search = "";
    public $filter = "";

    public function updatingSearch(){
        $this->resetPage();
    }

    public function edit($id)
    {
        return redirect()->route('articles.show', $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {
        Article::destroy($id);
        $this->render();
    }

    public function delete($id)
    {
        $this->dispatch('delete', ['label' => 'Esta seguro que desea eliminar el articulo?.', 'btn' => 'Eliminar', 'route' => route('articles.index'), 'id' => $id]);
    }

    public function render()
    {
        $pages = 15;
        $articles = [];
        switch ($this->filter) {
            case 'categories':
                $articles = Article::orderBy('id', 'desc')
                    ->when($this->search, function ($query) {
                        $query->whereHas('category', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->paginate($pages);
                break;
            case 'brands':
                $articles = Article::orderBy('id', 'desc')
                    ->when($this->search, function ($query) {
                        $query->whereHas('brand', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->paginate($pages);
                break;
            default:
                $articles = Article::orderBy('id', 'desc')->when($this->search, function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                })->paginate($pages);
                break;
        }


        return view('livewire.articles.table-articles', compact('articles'));
    }
}
