<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    {dump($user)}
    {#include "partials.header"}

    <h1>Welcome to {{ $frameworkName }}</h1>
        <p>{{ $message }}</p>

        <include-components.button variant="primary" color="blue">Click Me</include-components.button>

        <pre>
            {print_r($posts)}
        </pre>
        {#if $isLoggedIn}
        <!-- TODO: Add support for object type handlers and to get array property like the on bellow next to Welcome back -->
        <p>Welcome back,  {{$user->name}}</p>
        {{ $users["name"] }}
        {:else}
        <p>Please login</p>
        {/if}

        <h2>Posts</h2>
        {#each $posts as $post}
            <p>{{ $post['title'] }}</p>
        {:noitems}
            <p>No posts available.</p>
        {/each}

        {#set $totalPosts = count($posts)}
        <p>Total posts: {{ $totalPosts }}</p>

        <a $attributes>Visit Example</a>

        {#while ($counter > 0)}
        <p>{{ $counter }}</p>
            {#set $counter = $counter - 1}
        {/while}


        <h2>Numbers</h2>
        {#range 1 to 5 as $number}
            <p>Number: {{ $number }}</p>
        {/range}

        {Base\Helpers\StringHelper::capitalizeWords("jeremias nunez pozo")}





        <p>Current date: {date('Y-m-d', 'now')}</p>

        {debug($totalPosts)}
        {dump($user)}


        <script>
            var userData = {json($user)};
        </script>
</body>
</html>
