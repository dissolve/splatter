<article id="post-{{$post->post_id}}" class="{{$post->post_type}} h-entry {{ $post->draft == 1 ? 'draft':'' }} {{$post->deleted == 1 ? 'deleted':''}} " >
  <header class="entry-header">
    <?php if($post['post_type'] != 'listen'){ ?>
    <h1 class="entry-title p-name"><a href="{{$post->permalink()}}" class="u-url" title="Permalink to <?php echo $post['name']?>" ><?php echo $post['name']?></a></h1>
    <?php } ?>

<?php if($post['post_type'] == 'snark'){ ?>
    <h3 class="snark_alert">Sarcasm Alert</h3>
<?php } ?>

        <div class="entry-meta">      
      <span class="sep">Posted on </span>
        <a href="{{$post->permalink()}}" title="<?php echo date("g:i A", strtotime($post['published']))?>" class="u-url"> <time class="dt-published" datetime="<?php echo date("c", strtotime($post->published))?>" ><?php echo date("F j, Y", strtotime($post->published))?></time> </a>
        <address class="byline"> <span class="sep"> by </span> <span class="p-author h-card"><img alt='' src='{{$author['image']}}' class='u-photo avatar photo' height='40' width='40' /> <a class="u-url p-name" href="{{$author['url']}}" title="{{$author['name']}}">{{$author['name']}}</a></span></address>
        <?php if($post['in-reply-to']) { ?>
            <div class="repyto">
               In Reply To <a class="u-in-reply-to"  href="<?php echo $post['in-reply-to']?>">This</a>
            </div>
        <?php }  // end if in-reply-to?>
        </div><!-- .entry-meta -->
      </header><!-- .entry-header -->

     <?php if(isset($post['summary_html'])) { ?>
      <div class="p-summary">
     <?php } else { ?>
      <div class="entry-content e-content">
     <?php } ?>
        @if(isset($post->weight_value) && $post->weight_value)
         <h2 class="h-measure p-weight">
             Weight: <data class="p-num" value="<?php echo $post['weight_value']?>"><?php echo $post['weight_value']?></data><data class="p-unit" value="<?php echo $post['weight_unit']?>"><?php echo $post['weight_unit']?></data>
         </h2>
        @endif
        <?php if(isset($post['bookmark-of']) && !empty($post['bookmark-of'])) { ?>
            <i class="fa fa-bookmark-o"></i> 
            <a class="u-bookmark-of" href="<?php echo $post['bookmark-of']?>"><?php echo (isset($post['name']) && !empty($post['name'])?$post['name']:$post['bookmark-of'])?></a> <br>
        <?php } ?>
        <?php if(isset($post['following']) && !empty($post['following'])) { ?>
            <?php echo $post['author']['display_name'] . 
             ($post['post_type'] == 'follow' ? ' followed ' : ' unfollowed ' ) .
            '<a class="u-follow-of h-card" href="'.$post['following']['url'].'" >'.
            (isset($post['following']['photo']) && !empty($post['following']['photo']) ? '<img class="u-photo" style="width:40px;" src="'.$post['following']['photo'].'" />' : '' ).
            $post['following']['name'].
            '</a>'; ?>
        <?php } ?>
        <?php if(isset($post['like-of']) && !empty($post['like-of'])) { ?>
            <i class="fa fa-heart-o"></i> <a class="u-like-of" href="<?php echo $post['like-of']?>"><?php echo htmlentities($post['like-of']);?></a><br>
        <?php } ?>
        @foreach($post->media as $media)
            @if($media->type == 'photo')
                <img src="{{$media->path}}" class="u-photo photo-post" alt="{{$media->alt}}"/><br>
            @else
                <a href="{{$media->path}}">{{$media->type}}</a>
            @endif

        @endforeach
        <?php if($post['post_type'] == 'listen'){ ?>
            <?php echo 'I listend To <span class="song-title">'.$post['name'].'</span> by <span class="song-artist">'.$post['artist'].'</span>.'; ?>
      
        <?php  } ?>
        <?php if(isset($post['rsvp']) && !empty($post['rsvp'])) { ?>
            <i class="fa fa-calendar"></i>
               <a class="eventlink" href="<?php echo $post['in-reply-to']?>">Event</a>

<br>
            <i class="fa fa-envelope-o"></i>
            <data class="p-rsvp" value="<?php echo $post['rsvp']?>">
            <?php echo (strtolower($post['rsvp']) == 'yes' ? 'Attending' : 'Not Attending' );?>
            </data><br>
        <?php } ?>
            @if(isset($post->summary) && $post->summary)
                {!!html_entity_decode($post->summary)!!}
            @else
                {!!html_entity_decode($post->content)!!}
            @endif

            <?php if(isset($post['place_name']) && !empty($post['place_name'])){ 
            echo "<br>Checked In At ".$post['place_name'];
            } ?>
            <?php if(isset($post['location']) && !empty($post['location'])){
              $joined_loc = str_replace('geo:', '', $post['location']);
              $latlng = explode($joined_loc, ',');
              echo '<br>';
              echo '<img id="map" style="width: 200px; height: 200px" src="//maps.googleapis.com/maps/api/staticmap?zoom=13&size=200x200&maptype=roadmap&markers=size:mid%7Ccolor:blue%7C'. $joined_loc.'"/>';
            } ?>
      
      </div><!-- .entry-content -->
  
  <footer class="entry-meta">
             <?php if(isset($post['summary_html'])) {?>
                <a href="{{$post->permalink()}}" class="u-url">More...</a>
             <?php } ?>

             
    <?php if(!empty($post['reacjis']) ) { ?>
    <span id="general-reacjis">
        <?php foreach($post['reacjis'] as $reacji => $rdata){ ?>
        <span class="reacji-container">
                <span class="reacji"><?php echo $reacji?></span>
                <span class="reacji-count"><?php echo count($rdata)?></span>
        </span>
    <span class="sep"> | </span>
        <?php } ?>

        </span>
    <?php } ?>


    <?php if($post['comment_count'] > 0) { ?>
    <span class="comments-link"><a href="{{$post->permalink()}}#comments" title="Comments for <?php echo $post['name']?>"><i class="fa fa-comment-o"></i> <?php echo $post['comment_count'] ?></a></span>
    <span class="sep"> | </span>
    <?php } ?>

    <?php if($post['like_count'] > 0) { ?>
    <span class="likes-link"><a href="{{$post->permalink()}}#likes" title="Likes of <?php echo $post['name']?>"><i class="fa fa-heart-o"></i> <?php echo $post['like_count']?></a></span>
    <span class="sep"> | </span>
    <?php } ?>
  
    <?php if($post['repost_count'] > 0) { ?>
    <span class="reposts-link"><a href="{{$post->permalink()}}#reposts" title="reposts of <?php echo $post['name']?>"><i class="fa fa-retweet"></i> <?php echo $post['repost_count']?></a></span>
    <span class="sep"> | </span>
    <?php } ?>
  
  <?php if($post['categories']){ ?>
      <?php foreach($post['categories'] as $category) { ?>
          <?php if(isset($category['person_name'])){ ?>
              <span class="category-link"><a class="u-category h-card" href="<?php echo $category['url']?>" title="<?php echo $category['url']?>"><?php echo $category['person_name']?></a></span>
          <?php } else { ?>
              <span class="category-link"><a class="u-category" href="<?php echo $category['permalink']?>" title="<?php echo $category['name']?>"><?php echo $category['name']?></a></span>
          <?php } ?>
  
      <?php } // end for post_categories as category ?>
  <?php } // end if post_categories ?>
  <?php if(!empty($post['syndications'])){ ?>
    <div class="syndications">
    <?php foreach($post['syndications'] as $elsewhere){ ?>

      <?php if(isset($elsewhere['image'])){ ?>
      <a class="u-syndication" href="<?php echo $elsewhere['syndication_url']?>" ><img src="<?php echo $elsewhere['image']?>" title="<?php echo $elsewhere['site_name']?>" /></a>
      <?php } else { ?>
      <a class="u-syndication" href="<?php echo $elsewhere['syndication_url']?>" ><i class="fa fa-link"></i></a>
      <?php } ?>
      
    <?php } //end foreach ?>
    </div>
  <?php } ?>
    <div class="admin-controls">
    @if (isset($post->actions))
      <?php foreach($post['actions'] as $actiontype => $action){ ?>
      <indie-action do="<?php echo $actiontype?>" with="{{$post->permalink()}}">
      <a href="<?php echo $action['link'] ?>" title="<?php echo $action['title']?>"><?php echo $action['icon']?></a>
      </indie-action>
      <?php } ?>
    @endif
    </div>
  </footer><!-- #entry-meta --></article><!-- #post-<?php echo $post['post_id']?> -->