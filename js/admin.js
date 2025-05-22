jQuery(document).ready(function($) {
  // Apri il media uploader
  $(document).on('click', '.menu-item-image-upload', function(e) {
      e.preventDefault();
      var button = $(this);
      var imageField = button.siblings('.edit-menu-item-image');
      var imageWrapper = button.siblings('.menu-item-image-wrapper');
      var removeButton = button.siblings('.menu-item-image-remove');

      // Crea un frame media
      var frame = wp.media({
          title: 'Seleziona Immagine',
          button: {
              text: 'Usa questa immagine'
          },
          multiple: false
      });

      // Quando viene selezionata un'immagine
      frame.on('select', function() {
          var attachment = frame.state().get('selection').first().toJSON();
          imageField.val(attachment.id);
          imageWrapper.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;" />');
          removeButton.show();
      });

      frame.open();
  });

  // Rimuovi immagine
  $(document).on('click', '.menu-item-image-remove', function(e) {
      e.preventDefault();
      var button = $(this);
      var imageField = button.siblings('.edit-menu-item-image');
      var imageWrapper = button.siblings('.menu-item-image-wrapper');
      
      imageField.val('');
      imageWrapper.html('');
      button.hide();
  });
});