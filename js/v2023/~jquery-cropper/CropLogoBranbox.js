(function ($) {
  $.fn.CropLogoBranbox = function (setting) {
    var image_dimension_x = 800;
    var image_dimension_y = 800;
    var scaled_width = 0;
    var scaled_height = 0;
    var x1 = 0;
    var y1 = 0;
    var x2 = 0;
    var y2 = 0;
    var current_image = null;
    var image_filename = null;
    var image_type = null;
    var jcrop_api;
    var bottom_html = `<input type='file' id='jcrop-fileInput' name='files[]'/ accept='image/*'>
      <canvas id='jcrop-myCanvas' style='display:none;'></canvas>
      <div id='jcrop-modal'></div>
      <div id='jcrop-preview'>
        <div class='buttons'>
          <div class='cancel'></div>
          <div class='ok' style='display:none;'></div>
        </div>
        <div id="jcrop-image"></div>
        <div id="jcrop-error" style="color: red; padding:5px; background: white; font-size:14px; line-height: 1.2"></div>
      </div>`;
    $("body").append(bottom_html);

    //quanltv add
    var height =
      typeof setting.height != "undefined" && typeof setting.height == "number"
        ? setting.height
        : "auto";
    var width =
      typeof setting.width != "undefined" && typeof setting.width == "number"
        ? setting.width
        : "auto";
    var ratio =
      typeof setting.ratio != "undefined" && typeof setting.ratio == "number"
        ? setting.ratio
        : "auto";

    this.click(function () {
      $("#jcrop-fileInput").click();
    });

    $(document).ready(function () {
      //capture selected filename
      $("#jcrop-fileInput").change(function (click) {
        imageUpload($("#jcrop-preview #jcrop-image").get(0));
        // Reset input value
        $(this).val("");
      });

      //ok listener
      $(".ok").click(function () {
        preview();
        $("#jcrop-preview").delay(100).hide();
        $("#jcrop-modal").hide();
        jcrop_api.destroy();
        reset();
      });

      //cancel listener
      $(".cancel").click(function (event) {
        $("#jcrop-preview").delay(100).hide();
        $("#jcrop-modal").hide();
        jcrop_api.destroy();
        reset();
      });
    });

    function reset() {
      scaled_width = 0;
      scaled_height = 0;
      x1 = 0;
      y1 = 0;
      x2 = 0;
      y2 = 0;
      current_image = null;
      image_filename = null;
      image_type = null;
    }

    function imageUpload(dropbox) {
      var file = $("#jcrop-fileInput").get(0).files[0];

      var imageType = /image.*/;

      if (file.type.match(imageType)) {
        var reader = new FileReader();
        image_filename = file.name;
        image_type = file.type;

        reader.onload = function (e) {
          // Clear the current image.
          $("#photo").remove();

          // Create a new image with image crop functionality
          current_image = new Image();
          current_image.src = reader.result;
          current_image.id = "photo";
          current_image.style["maxWidth"] = image_dimension_x + "px";
          current_image.style["maxHeight"] = image_dimension_y + "px";
          current_image.onload = function () {
            // Calculate scaled image dimensions
            if (
              current_image.width > image_dimension_x ||
              current_image.height > image_dimension_y
            ) {
              if (current_image.width > current_image.height) {
                scaled_width = image_dimension_x;
                scaled_height =
                  (image_dimension_x * current_image.height) /
                  current_image.width;
              }
              if (current_image.width < current_image.height) {
                scaled_height = image_dimension_y;
                scaled_width =
                  (image_dimension_y * current_image.width) /
                  current_image.height;
              }
              if (current_image.width == current_image.height) {
                scaled_width = image_dimension_x;
                scaled_height = image_dimension_y;
              }
            } else {
              scaled_width = current_image.width;
              scaled_height = current_image.height;
            }

            // set the image size to the scaled proportions which is required for at least IE11
            current_image.style["width"] = scaled_width + "px";
            current_image.style["height"] = scaled_height + "px";

            // Position the modal div to the center of the screen
            $("#jcrop-modal").css("display", "block");
            var window_width = $(window).width() / 2 - scaled_width / 2 + "px";
            var window_height =
              $(window).height() / 2 - scaled_height / 2 + "px";

            // Show image in modal view
            $("#jcrop-preview").css("top", window_height);
            $("#jcrop-preview").css("left", window_width);
            $("#jcrop-preview").show(500);

            ias = $(this).Jcrop(
              {
                onSelect: showCoords,
                onChange: showCoords,
                bgColor: "#747474",
                bgOpacity: 0.4,
                setSelect: [0, 0, 100, 100],
              },
              function () {
                jcrop_api = this;
              }
            );
          };

          // Add image to dropbox element
          dropbox.appendChild(current_image);
        };

        reader.readAsDataURL(file);
      } else {
        dropbox.innerHTML = "File not supported!";
      }
    }

    function showCoords(c) {
      //gan x y
      x1 = c.x;
      y1 = c.y;
      x2 = c.x2;
      y2 = c.y2;
      let ratio_crop = c.w / c.h;

      // ratio crop lon hon ratio setting -> báo lỗi + ẩn nút OK
      if (ratio_crop > ratio) {
        $("#jcrop-error").html(`▲ Ảnh quá ngắn hãy tăng chiều cao ảnh.`).show();
        $('#jcrop-preview .buttons .ok').hide();
      }
      // tile crop phu hop
      else {
        $("#jcrop-error").hide();
        $('#jcrop-preview .buttons .ok').show();
      }
    }

    function preview() {
      // Set canvas
      var canvas = document.getElementById("jcrop-myCanvas");
      var context = canvas.getContext("2d");

      // Delete previous image on canvas
      context.clearRect(0, 0, canvas.width, canvas.height);

      // Set selection width and height
      var sw = x2 - x1;
      var sh = y2 - y1;

      //quanltv
      //calculator resize, co dinh chieu cao
      var sw_resize, sh_resize;
      if (height == "auto" && width == "auto") {
        sw_resize = sw;
        sh_resize = sh;
      } else if (height > 0 && width == "auto") {
        sh_resize = height;
        sw_resize = (sw * sh_resize) / sh;
      } else if (width > 0 && height == "auto") {
        sw_resize = width;
        sh_resize = (sh * sw_resize) / sw;
      } else {
        sw_resize = width;
        sh_resize = height;
      }

      // Set image original width and height
      var imgWidth = current_image.naturalWidth;
      var imgHeight = current_image.naturalHeight;

      // Set selection koeficient
      var kw = imgWidth / $("#jcrop-preview").width();
      var kh = imgHeight / $("#jcrop-preview").height();

      // Set canvas width and height and draw selection on it
      canvas.width = sw_resize;
      canvas.height = sh_resize;
      context.drawImage(
        current_image,
        x1 * kw,
        y1 * kh,
        sw * kw,
        sh * kh,
        0,
        0,
        sw_resize,
        sh_resize
      );

      // Convert canvas image to normal img
      var dataUrl = canvas.toDataURL(image_type);
      var imageFoo = document.createElement("img");
      imageFoo.src = dataUrl;

      // Append it to the body element
      $("#jcrop-preview").delay(100).hide();
      $("#jcrop-modal").hide();

      //quanltv add: thanh cong -> call back
      setting.callback({
        original: {
          filename: image_filename,
          type: image_type,
          base64: dataUrl,
        },
        crop: { x: x1 * kw, y: y1 * kh, width: sw * kw, height: sh * kh },
      });
    }

    $(window).resize(function () {
      // Position the modal div to the center of the screen
      var window_width = $(window).width() / 2 - scaled_width / 2 + "px";
      var window_height = $(window).height() / 2 - scaled_height / 2 + "px";

      // Show image in modal view
      $("#jcrop-preview").css("top", window_height);
      $("#jcrop-preview").css("left", window_width);
    });
  };
})(jQuery);
