## Projeto Agenda
<pre>Em construção v 0.0.0</pre>
<p>Trata-se de uma API de demonstração para agenda multiusuário, nela os usuários com permissão administrativa poderão:</p>
<ul>
    <li>Ver todos os eventos</li>
    <li>Criar eventos na agenda</li>
    <li>Alterar eventos na agenda</li>
    <li>Deletar eventos na agenda(soft delete apenas)</li>
</ul>
<p>Os usuários sem permissão administrativa poderão:
<ul>
    <li>Ver todos os eventos na agenda que é proprietário</li>
    <li>Criar eventos na agenda na agenda que é proprietário</li>
    <li>Alterar eventos na agenda que é proprietário</li>
    <li>Apagar eventos na agenda que é proprietário</li>
</ul>
<p>Sempre que uma evento for agendado o proprietário da agenda receberá aviso por e-mail, também os usuários administrativos receberão</p>
<p>As permissões serão aplicadas através de Policies e Gates</p>
<p>Os envio de mensagens deve acontecer de forma assincrona com o uso de jobs.
<p>Futuramente vamos implementar a conexão com a API do Google Agenda.

### Segurança

<p>Usamos o Sanctum para este projeto, portanto faça a instalação:
<code>composer require laravel/sanctum</code></p>
<p>Publique:</p>
<code>php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"</code>
<p>Faça o migrate:</p>
<code>php artisan migrate</code>
<p>Se estiver usando o sail:
<code>./vendor/bin/sail php artisan migrate</code>

#### Sail

<p>Neste projeto existe um arquivo shellscript para o sail, caso deseje usar de as permissões necessárias e poderá usar como  ./sailup para iniciar os containeres e ./saildown para finaliza-los.
