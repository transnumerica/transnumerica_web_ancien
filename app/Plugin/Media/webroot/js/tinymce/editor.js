jQuery(function($){
  var buttons = "code,|,bold,italic,underline,strikethrough,|,formatselect,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,image,|,codehighlighting";
  if( $('#explorer').length == 0 ){
    buttons = buttons.replace('image,','');
  }
  $('textarea.tinymce').each(function(){
    tinymce.init({
      selector: "textarea#" + $(this).attr('id'),
      menubar: false,
      relative_urls: false,
      plugins: [
           "advlist autolink link image gallery lists hr spellchecker",
           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
           "save table contextmenu directionality template paste textcolor fullscreen"
      ],
      gallery_path : $('#explorer').val() + '/editor:tinymce?id=' + $(this).attr('id'),
      content_css  : $('#editorcss').val(),
      toolbar: "insertfile | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link gallery | print preview media fullpage | forecolor fullscreen",
      image_advtab: true
    });
  })

});


function send_to_tinymce(content, win){
    var ed = tinyMCE.activeEditor;
    ed.execCommand('mceInsertContent', false, content);
    ed.windowManager.close(win);
}