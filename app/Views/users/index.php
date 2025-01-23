<h1>List of Users</h1>

{#each $users as $user}
    <article>
        {print_r($user->name)}
    </article>
{/each}

{{$pagination}}
