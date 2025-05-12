jQuery(function($){
  var frame;
  $(document).on('click', '.awdx-upload-images', function(e){
    e.preventDefault();
    if (frame) { frame.open(); return; }
    frame = wp.media({
      title: 'Select Images',
      multiple: true,
      library: { type: 'image' }
    });
    frame.on('select', function(){
      var selection = frame.state().get('selection').toJSON();
      selection.forEach(function(img){
        var li = $('<li/>');
        li.append('<img src="'+img.url+'" width="80">')
          .append('<input type="hidden" name="awdx_images[]" value="'+img.url+'">')
          .append('<input type="text"   name="awdx_captions[]" placeholder="Caption">')
          .append('<button type="button" class="button awdx-remove-image">Remove</button>');
        $('.awdx-image-list').append(li);
      });
    });
    frame.open();
  });

  $(document).on('click', '.awdx-remove-image', function(){
    $(this).closest('li').remove();
  });
});
