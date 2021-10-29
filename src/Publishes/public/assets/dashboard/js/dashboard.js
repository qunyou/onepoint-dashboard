$(document).ready(function() {
    window_width = $(window).width();
    $sidebar = $('.sidebar');
    image_src = $sidebar.data('image');
    sidebar_container = '<div class="sidebar-background" style="background-image: url(' + image_src + ') "/>';
    $sidebar.append(sidebar_container);

    if (window_width <= 991) {
        $("#wrapper").removeClass("active");
        $(".sidebarExpand").removeClass("fa-angle-double-left");
        $(".sidebarExpand").addClass("fa-angle-double-right");
        $('.collapse.search').collapse('hide');
    }

    // Fixes sub-nav not working as expected on IOS
    $('body').on('touchstart.dropdown', '.dropdown-menu', function(e) {
        e.stopPropagation();
    });

    $('#select_all').on('click', function () {
        if (this.checked) {
            $('.checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    $('.checkbox').on('click', function () {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#select_all').prop('checked', true);
        } else {
            $('#select_all').prop('checked', false);
        }
    });

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
        $(".sidebarExpand").toggleClass("fa-angle-double-left");
        $(".sidebarExpand").toggleClass("fa-angle-double-right");
    });

    $('.table-expand-content').hide();
    $('.table-expand').on('click', function () {
        $(this).find(".fas").toggleClass('fa-plus-circle');
        $(this).find(".fas").toggleClass('fa-minus-circle');
        $(this).closest("tr").next().toggle();
    });

    $('.form-submit').click(function() {
        $(this).attr('disabled','disabled');
    });

    $('#form-submit').submit(function() {
        $("#form-button", this)
          .html("Please Wait...")
          .attr('disabled', 'disabled');
        return true;
    });
});

$(window).resize(function() {
    if ($(window).width() <= 991) {
        $("#wrapper").removeClass("active");
        $(".sidebarExpand").removeClass("fa-angle-double-left");
        $(".sidebarExpand").addClass("fa-angle-double-right");
        $('.collapse.search').collapse('hide');
    } else {
        $("#wrapper").addClass("active");
    }
});
