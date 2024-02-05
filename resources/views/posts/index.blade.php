<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('posts.create') }}">

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            New Post
                        </button>
                    </a> <br><br>
                        <form action="{{ route('posts') }}" method="GET">
                        <div class="mb-4">
                            <input type="text" name="query" placeholder="Search by title" class="border rounded-md px-4 py-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Search
                            </button>
                        </div>
                    </form>
                    
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __("Posts") }}</h2>

                    @if($posts->isEmpty())
                        <p>No posts found.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($posts as $post)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $post->title }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $post->description }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $post->author->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap"><a href="{{ 
                                        route('posts.show', $post->id)}}">
                                            Show</a>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @if (auth()->user() && auth()->user()->id == $post->author->id)
                                            <a href="{{ 
                                                route('posts.edit', $post->id)}}">
                                                    Edit</a>
                                            <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" class="text-red-500">Delete</button>
                                            </form>
                                        @endif
                                        
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
