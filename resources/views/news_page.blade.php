<x-layout>
    @if (auth()->user()->isAdmin !== 0)
        <div style="max-width: 600px; margin: 10px auto;">
        <label for="make_news"> Create News for Your Company: </label>
        <form name="make_news" method="POST" action="/create_news" style="display: flex; flex-direction:column;">
            @csrf
            <input name="title" type="text" placeholder="Article Title">
            <textarea name="body" placeholder="Body Text" style="height: 100px; border:1px solid black; border-radius: 4px; margin: 3px;"> </textarea>
            <button style="width: 70px; margin: 5px;"> Submit </button>
        </form>
    </div>
    @endif
    

    <div>
        <span style="display: flex; max-width: 650px; margin: 5px auto;">  {{$news->links()}}  </span>
            @foreach ($news as $article)
            <div class="article"> 
                <span class="article_date"> {{date('M d, Y', strtotime($article->created_at))}} </span>
                    <div>
                    <span class="news_title"> {{$article->title}} </span>
                    <p class="news_body"> {{$article->body}} </p>
                    <span class="author"> -<em>{{$article->author}}</em> </span>
                    </div>
            </div>
            @endforeach 
            
    </div>

</x-layout>