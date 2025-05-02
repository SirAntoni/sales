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
        $limit = 15;
        $articles = Article::query()
            ->with(['category:id,name', 'brand:id,name'])
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('detail', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('brand', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate($limit);

        return view('livewire.articles.table-articles', compact('articles'));
    }
}
