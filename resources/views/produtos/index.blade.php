<!-- Formulário de criação de produto -->

@extends('layouts.app')

@section('content')
<h1>Produtos</h1>

<style>
input,
select {
  margin: 4px;
}

.box {
  border: 1px solid #ccc;
  padding: 10px;
  margin-bottom: 10px;
}

.produto-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.toggle-btn {
  font-weight: bold;
  user-select: none;
  cursor: pointer;
  margin-left: 10px;
}

.variacoes {
  margin-top: 10px;
  margin-left: 20px;
  display: none;
}

.variacoes .actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-remover {
  background: none;
  border: none;
  color: red;
  cursor: pointer;
  font-weight: bold;
  padding: 0;
}

.acao-btns {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
</style>

{{-- Formulário de criação de produto --}}
<form method="POST" action="/produtos">
  @csrf
  <input name="nome" placeholder="Nome do produto" class="form-control mb-2" style="max-width: 300px;">
  <input name="preco" placeholder="Preço" class="form-control mb-2" style="max-width: 300px;">
  <div id="variacoes">
    <div>
      <input name="variacoes[]" placeholder="Variação" class="form-control mb-2"
        style="max-width: 300px; display: inline-block;">
      <input name="quantidades[]" placeholder="Estoque" class="form-control mb-2"
        style="max-width: 150px; display: inline-block;">
    </div>
  </div>
  <button type="button" onclick="addVariacao()" class="btn btn-secondary mb-3">+ Variação</button>
  <button type="submit" class="btn btn-primary mb-3">Criar Produto</button>
</form>

<hr>

{{-- Lista de produtos --}}
@foreach ($produtos as $produto)
<div class="box">
  <div class="produto-header">
    <div style="cursor: pointer;" onclick="toggleVariacoes('{{ $produto->id }}')">
      {{ $produto->nome }} - R$ {{ number_format($produto->preco, 2, ',', '.') }}
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
      <span class="toggle-btn" id="toggle-btn-{{ $produto->id }}"
        onclick="toggleVariacoes('{{ $produto->id }}')">[+]</span>

      <form method="POST" action="/produtos/{{ $produto->id }}"
        onsubmit="return confirm('Tem certeza que deseja remover este produto?');" style="margin: 0;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-remover">Remover</button>
      </form>
    </div>
  </div>

  {{-- Variações do produto --}}
  <div id="variacoes-{{ $produto->id }}" class="variacoes">
    @if($produto->estoques->count() > 0)
    @foreach ($produto->estoques as $estoque)
    <div class="mb-3 variacoes-actions"
      style="display: flex; align-items: center; justify-content: space-between; max-width: 100%;">
      <div>
        <strong>Variação:</strong> {{ $estoque->variacao }}<br>
        <strong>Estoque:</strong> {{ $estoque->quantidade }}
      </div>

      <div class="actions" style="display: flex; align-items: center; gap: 8px; max-width: 500px;">

        <form action="{{ route('carrinho.adicionar') }}" method="POST" style="margin: 0;">
          @csrf
          <input type="hidden" name="produto_id" value="{{ $produto->id }}">
          <input type="hidden" name="estoque_id" value="{{ $estoque->id }}">
          <input type="hidden" name="preco" value="{{ $produto->preco }}">
          <input type="number" name="quantidade" value="1" min="1" max="{{ $estoque->quantidade }}" class="form-control"
            style="width: 80px; display: inline-block;" required>
          <button type="submit" class="btn btn-success">Comprar</button>
        </form>

        <form method="POST" action="/produtos/{{ $produto->id }}" style="margin: 0;">
          @csrf
          @method('PUT')
          <input type="hidden" name="nome" value="{{ $produto->nome }}">
          <input type="hidden" name="preco" value="{{ $produto->preco }}">
          <input type="hidden" name="estoques[{{ $estoque->id }}][variacao]" value="{{ $estoque->variacao }}">
          <input type="number" name="estoques[{{ $estoque->id }}][quantidade]" value="{{ $estoque->quantidade }}"
            min="0" class="form-control" style="width: 100px; display: inline-block;">
          <button type="submit" class="btn btn-warning">Atualizar</button>
        </form>

        <form method="POST" action="{{ route('variacoes.remover', $estoque->id) }}"
          onsubmit="return confirm('Remover essa variação?');" style="margin: 0;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Remover</button>
        </form>
      </div>

    </div>
    @endforeach
    @else
    <p><em>Nenhuma variação cadastrada.</em></p>
    @endif
    <!-- Botão para mostrar o formulário -->
    <button type="button" class="btn btn-secondary" onclick="mostrarFormularioNovaVariacao('{{$produto->id}}')">
      + Nova variação
    </button>


    <!-- Formulário escondido inicialmente -->
    <div id="form-nova-variacao-{{ $produto->id }}" style="display: none; margin-top: 10px;">
      <form method="POST" action="{{ route('produtos.adicionar.variacao', $produto->id) }}"
        class="d-flex align-items-center gap-2 flex-wrap">
        @csrf
        <input name="variacao" placeholder="Nova variação" class="form-control" style="max-width: 300px;" required>
        <input name="quantidade" type="number" placeholder="Quantidade" class="form-control" style="max-width: 150px;"
          required min="0">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </form>
    </div>
  </div>
</div>
@endforeach


<script>
function addVariacao() {
  const container = document.getElementById('variacoes');
  const div = document.createElement('div');
  div.innerHTML =
    '<input name="variacoes[]" placeholder="Variação" class="form-control mb-2" style="max-width: 300px; display: inline-block;">' +
    '<input name="quantidades[]" placeholder="Estoque" class="form-control mb-2" style="max-width: 150px; display: inline-block;">';
  container.appendChild(div);
}

function toggleVariacoes(produtoId) {
  const div = document.getElementById('variacoes-' + produtoId);
  const btn = document.getElementById('toggle-btn-' + produtoId);
  if (div.style.display === 'none' || div.style.display === '') {
    div.style.display = 'block';
    btn.textContent = '[-]';
  } else {
    div.style.display = 'none';
    btn.textContent = '[+]';
  }
}


function addNovaVariacao(produtoId) {
  const container = document.getElementById('novas-variacoes-' + produtoId);

  const div = document.createElement('div');
  div.classList.add('mb-3');
  div.innerHTML = `
    <form method="POST" action="/produtos/\${produtoId}/adicionar-variacao" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
      @csrf
      <input name="variacao" placeholder="Nova variação" class="form-control" style="max-width: 300px;">
      <input name="quantidade" type="number" placeholder="Quantidade" class="form-control" style="max-width: 150px;">
      <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
  `;
  container.appendChild(div);
}

function mostrarFormularioNovaVariacao(produtoId) {
  const formDiv = document.getElementById('form-nova-variacao-' + produtoId);
  formDiv.style.display = formDiv.style.display === 'none' ? 'block' : 'none';
}
</script>


@endsection