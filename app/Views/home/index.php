<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    {#include "partials.header"}

    <h1>Welcome to {{ $frameworkName }}</h1>
        <p>{{ $message }}</p>

        <include-components.button variant="primary" color="blue">Click Me</include-components.button>

        <pre>
            {print_r($posts)}
        </pre>

        {#if $isLoggedIn}
        <p>Welcome back, {{ $user }}</p>
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

        <!-- TODO: Add support for Helpershandlers to be used as template syntax {#if EnvHelper::isEnvironment->development}
            <p>Development mode: Debugging enabled.</p>
        {/if} -->

</body>
</html>
