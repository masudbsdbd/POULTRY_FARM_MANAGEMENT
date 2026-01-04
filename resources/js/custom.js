document.addEventListener("DOMContentLoaded", function () {
    let imageLocation = "url({{ asset('uploads/default-picker.png') }})";
    function proPicURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = $(input)
                    .parents(".thumb")
                    .find(".profilePicPreview");
                $(preview).css(
                    "background-image",
                    "url(" + e.target.result + ")"
                );
                $(preview).addClass("has-image");
                $(preview).hide();
                $(preview).fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".profilePicUpload").on("change", function () {
        proPicURL(this);
    });

    $(".remove-image").on("click", function () {
        $(this)
            .parents(".profilePicPreview")
            .css("background-image", imageLocation);
        $(this).parents(".profilePicPreview").removeClass("has-image");
        $(this).parents(".thumb").find("input[type=file]").val("");
    });

    $("form").on("change", ".file-upload-field", function () {
        $(this)
            .parent(".file-upload-wrapper")
            .attr(
                "data-text",
                $(this)
                    .val()
                    .replace(/.*(\/|\\)/, "")
            );
    });

    var inputElements = $("input,select,textarea");

    $.each(inputElements, function (index, element) {
        element = $(element);
        if (!element.hasClass("profilePicUpload") && !element.attr("id")) {
            element
                .closest(".form-group")
                .find("label")
                .attr("for", element.attr("name"));
            element.attr("id", element.attr("name"));
        }
    });
});
