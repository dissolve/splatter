
<div id="comments" class="comments">
  @foreach($comments as $comment)
    <div class="comment u-comment h-cite">
      <div class='comment_header'>
        <span class="minicard h-card u-author">
          <img class='comment_author u-photo' src="{{$comment->author->image ?: '/image/person.png'}}" />
          <a class="p-name u-url" href="{{$comment->author->url ?: $comment->url}}" rel="nofollow" title="{{$comment->author->name ?: 'View Author'}}" >
            {{$comment->author->name ?: 'someone'}}
          </a>
        </span>

        <a href="{{$comment->url}}" class="u-url permalink">
          <time class="date dt-published" datetime="{{$comment->published}}">{{date("F j, Y g:i A", strtotime($comment->published)) }}</time>
        </a>
        @if(!empty($comment->vouch_url) )
          <a href="{{$comment->vouch_url}}" class="vouch">Vouched</a>
        @endif
<!-- todo: indieactions -->
      </div>
      <div class='comment_body p-content p-name'>
        {{$comment->content}}
      </div>

    @if( $comment->syndications->count() > 0)
      <div class="syndications">
        @foreach($comment->syndications as $elsewhere)
          @if(isset($elsewhere->site))
            <a class="u-syndication" href="{{$elsewhere->url}}" ><img style='height:20px;width:20px;' src="{{$elsewhere->site->image}}" title="{{$elsewhere->site->name}}" /></a>
          @else
            <a class="u-syndication" href="{{$elsewhere->url}}" ><i class="fa fa-link"></i></a>
          @endif
        @endforeach
      </div>
    @endif

      @if(!empty($comment->comments) )
        @foreach($comment->comments as $subcomment)
          <div class="subcomment u-comment h-cite">
            <div class='comment_header'>
              <span class="minicard h-card u-author">
                <img class='comment_author u-photo' src="{{$subcomment->author->image ?: '/image/person.png'}}" />
                <span class="p-name u-url" href="{{$subcomment->author->url ?: $subcomment->url}}" rel="nofollow" title="{{$subcomment->author->name ?: 'View Author'}}" >
                  {{$subcomment->author->name ?: 'someone'}}
                </span>

                @if( $subcomment->syndications->count() > 0)
                  <div class="syndications">
                    @foreach($subcomment->syndications as $elsewhere)
                      @if(isset($elsewhere->site))
                        <a class="u-syndication" href="{{$elsewhere->url}}" ><img style='height:20px;width:20px;' src="{{$elsewhere->site->image}}" title="{{$elsewhere->site->name}}" /></a>
                      @else
                        <a class="u-syndication" href="{{$elsewhere->url}}" ><i class="fa fa-link"></i></a>
                      @endif
                    @endforeach
                  </div>
                @endif

              </span>
              <a href="{{$subcomment->url}}" class="u-url permalink">
                <time class="date dt-published" datetime="{{$subcomment->published}}">{{date("F j, Y g:i A", strtotime($subcomment->published)) }}</time>
              </a>
            </div>
            <div class='comment_body p-content p-name'>
              {{$subcomment->content}}
            </div>
          </div>
        @endforeach
      @endif
    </div>
  @endforeach
</div>
