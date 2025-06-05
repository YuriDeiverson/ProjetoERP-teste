@component('mail::message')
# Compra Finalizada

Uma nova compra foi finalizada com o seguinte endereço:

*CEP:* {{ $endereco['cep'] }}
*Rua:* {{ $endereco['logradouro'] }}
*Bairro:* {{ $endereco['bairro'] ?? 'N/A' }}
*Cidade:* {{ $endereco['localidade'] }}
*Estado:* {{ $endereco['uf'] }}

@endcomponent


<!-- Tentei fazer o envio de email pelo mailgun só que tive algumas complicações durante a configuração do dominio -->