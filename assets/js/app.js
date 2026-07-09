
(function($) {
    'use strict';

    $.ajaxPrefilter(function(options, originalOptions) {
        if (/^(POST|PUT|DELETE)$/i.test(options.type) &&
            typeof csrf_token_name !== 'undefined' &&
            typeof csrf_hash !== 'undefined') {
            var token = {};
            token[csrf_token_name] = csrf_hash;
            if (typeof originalOptions.data === 'string') {
                options.data = originalOptions.data +
                    (originalOptions.data ? '&' : '') + $.param(token);
            } else {
                options.data = $.extend({}, originalOptions.data, token);
            }
        }
    });

    $(document).ready(function() {

        console.log('window.base_url:', window.base_url);
        console.log('base_url test:', base_url('notifications/get_recent'));

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });

        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        $('#notificationDropdown').on('click', function(e) {
            if ($(this).attr('href') === '#') {
                e.preventDefault();
            }
            loadNotifications();
        });

        updateNotificationBadge();

        $('[data-confirm]').on('click', function(e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
                return false;
            }
        });

        $('textarea[data-autoresize]').each(function() {
            var offset = this.offsetHeight - this.clientHeight;
            var resizeTextarea = function(el) {
                $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            $(this).on('keyup input', function() {
                resizeTextarea(this);
            }).removeAttr('data-autoresize');
        });

        if ($.fn.datepicker) {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        }

        $('.status-badge-clickable').on('click', function() {
            var taskId = $(this).data('task-id');
            var currentStatus = $(this).data('status');
            updateTaskStatus(taskId, currentStatus);
        });

        $('input[type="file"]').on('change', function(e) {
            var fileName = e.target.files[0]?.name;
            if (fileName) {
                $(this).next('.custom-file-label').html(fileName);
            }
        });

        var searchTimeout;
        $('.search-input').on('keyup', function() {
            var $this = $(this);
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                $this.closest('form').submit();
            }, 500);
        });

        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 500);
                    return false;
                }
            }
        });

    });

    function loadNotifications() {
        var loadingHtml = '<li><h6 class="dropdown-header">Notifikasi</h6></li>' +
            '<li><hr class="dropdown-divider"></li>' +
            '<li><div class="text-center p-3"><div class="spinner-border spinner-border-sm" role="status"></div></div></li>';
        $('#notificationList').html(loadingHtml);

        $.ajax({
            url: base_url('notifications/get_recent'),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var html = '<li><h6 class="dropdown-header">Notifikasi</h6></li>' +
                    '<li><hr class="dropdown-divider"></li>' +
                    response.html;
                $('#notificationList').html(html);
                updateNotificationBadge();
            },
            error: function(xhr, status, error) {
                console.error('Notification load error:', xhr.responseText);
                $('#notificationList').html(
                    '<li><h6 class="dropdown-header">Notifikasi</h6></li>' +
                    '<li><hr class="dropdown-divider"></li>' +
                    '<li><div class="text-center p-3 text-danger">Gagal memuat notifikasi: ' + error + '</div></li>'
                );
            }
        });
    }

    function updateNotificationBadge() {
        $.ajax({
            url: base_url('notifications/count'),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var badge = $('.nav-link .badge');
                if (response.count > 0) {
                    badge.text(response.count > 99 ? '99+' : response.count).show();
                } else {
                    badge.hide();
                }
            }
        });
    }

    function markAsRead(notificationId) {
        $.ajax({
            url: base_url('notifications/mark_read/' + notificationId),
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                updateNotificationBadge();
            }
        });
    }

    function updateTaskStatus(taskId, newStatus) {
        $.ajax({
            url: base_url('tasks/update_status/' + taskId),
            method: 'POST',
            data: { status: newStatus },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Gagal memperbarui status: ' + (response.message || 'Terjadi kesalahan'));
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memperbarui status');
            }
        });
    }

    function deleteItem(url, message) {
        if (confirm(message || 'Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus: ' + (response.message || 'Terjadi kesalahan'));
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus');
                }
            });
        }
    }

    function formatDate(dateString) {
        var options = { year: 'numeric', month: 'short', day: 'numeric' };
        var date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
    }

    function formatDateTime(dateString) {
        var options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        var date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
    }

    function daysRemaining(deadline) {
        var today = new Date();
        var deadlineDate = new Date(deadline);
        var diffTime = deadlineDate - today;
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }

    function showLoading() {
        var spinner = '<div class="spinner-overlay">' +
            '<div class="spinner-border text-light" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            '</div>' +
            '</div>';
        $('body').append(spinner);
    }

    function hideLoading() {
        $('.spinner-overlay').remove();
    }

    function showToast(message, type) {
        type = type || 'info';
        var bgColor = 'bg-' + type;

        var toast = '<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">' +
            '<div class="toast show" role="alert">' +
            '<div class="toast-header ' + bgColor + ' text-white">' +
            '<strong class="me-auto">Notifikasi</strong>' +
            '<button type="button" class="btn-close" data-bs-dismiss="toast"></button>' +
            '</div>' +
            '<div class="toast-body">' + message + '</div>' +
            '</div>' +
            '</div>';

        $('.toast-container').remove();
        $('body').append(toast);

        setTimeout(function() {
            $('.toast-container').remove();
        }, 3000);
    }

    window.markAsRead = markAsRead;
    window.deleteItem = deleteItem;
    window.showLoading = showLoading;
    window.hideLoading = hideLoading;
    window.showToast = showToast;
    window.formatDate = formatDate;
    window.formatDateTime = formatDateTime;
    window.daysRemaining = daysRemaining;

})(jQuery);

function getCsrfToken() {
    return typeof csrf_hash !== 'undefined' ? csrf_hash : '';
}

function base_url(segment) {
    var baseUrl = (typeof window.base_url !== 'undefined') ? window.base_url : window.location.origin + '/';
    if (segment) {
        baseUrl = baseUrl.replace(/\/+$/, '');
        segment = segment.replace(/^\/+/, '');
        return baseUrl + '/' + segment;
    }
    return baseUrl;
}
