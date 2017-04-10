(function ($, window, document, undefined) {
    var pluginName = "reminder",
        defaults = {
            'listUrl': undefined,
            'deleteUrl': undefined,
            'updateUrl': undefined,
            'getCountUrl': undefined,
            'updateText': undefined,
            'doNotRemindText': undefined,
            'loaderTemplate': undefined,
            'updateInterval': 60 * 1000 // 1 minute
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.intervalId = null;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            var _this = this;
            this.getRemindersListListener();
            this.updateReminderListener();
            this.deleteReminderListener();
            this.updateGridColumnListener();
            this.intervalId = setInterval(function () {
                if (window.Pace !== undefined) {
                    Pace.ignore(_this.updateCounts.bind(_this));
                } else {
                    _this.updateCounts();
                }
            }, this.settings.updateInterval);
        },
        getDeferListener: function () {
            var _this = this;
            $(_this.element).find('.reminder-defer').each(function (i, elem) {
                var $elem = $(elem);
                $elem.popover({
                    content: $('#popover-' + $elem.data('reminder-id')).html(),
                    html: true,
                    trigger: 'click'
                });
            });
            $(document).on('click', '.reminder-defer', function (ev) {
                ev.preventDefault();
                $('.reminder-defer').not(this).popover('hide');

                return false;
            });
        },

        // Get Reminders
        getRemindersListListener: function () {
            var _this = this;
            $(_this.element).on('shown.bs.dropdown', function (ev) {
                _this.getRemindersList(ev);
            });
            $(_this.element).on('hide.bs.dropdown', function (ev) {
                $('.reminder-defer').popover('hide');
            });
        },
        getRemindersList: function (ev) {
            var _this = this;
            var elem = $('li.reminder-body');
            $.ajax({
                url: _this.settings.listUrl,
                method: 'POST',
                data: {
                    'offset': _this.clientUtcOffset()
                },
                beforeSend: function () {
                    $('.reminder-defer').popover('hide');
                    elem.html(_this.settings.loaderTemplate);
                },
                success: function (data) {
                    _this.updateCounts();
                    elem.html(data);
                    _this.getDeferListener();
                }
            });
        },

        // Delete reminder
        deleteReminderListener: function () {
            var _this = this;
            $(document).on('click', '.reminder-delete', function (ev) {
                ev.preventDefault();
                var elem = $(this);
                var id = elem.data('reminder-id');
                _this.deleteReminder(id);

                return false;
            });
        },
        deleteReminder: function (id) {
            var _this = this;
            $.ajax({
                url: _this.settings.deleteUrl,
                type: 'POST',
                data: {
                    'Reminder': {
                        'id': id
                    }
                },
                success: function () {
                    _this.updateCounts();
                    _this.getRemindersList();
                    _this.notify('delete');
                }
            });
        },

        // Update reminder
        updateReminderListener: function () {
            var _this = this;
            $(document).on('click', '.reminder-update', function (ev) {
                ev.preventDefault();
                var elem = $(this);
                var id = elem.data('reminder-id');
                var action = elem.data('reminder-action');
                _this.updateReminder(id, action);

                return false;
            });
        },
        updateReminder: function (id, action) {
            var _this = this;
            $.ajax({
                url: _this.settings.updateUrl,
                type: 'POST',
                data: {
                    'Reminder': {
                        'id': id,
                        'reminderChange': action,
                        'clientTimeZone': _this.clientUtcOffset()
                    }
                },
                success: function (count) {
                    _this.updateCounts();
                    _this.getRemindersList();
                    _this.notify('update');
                }
            });
        },

        // Other functions
        notify: function (action) {
            var _this = this;
            new PNotify({
                text: action === 'update' ? _this.settings.updateText : _this.settings.doNotRemindText,
                type: 'success',
                buttons: {
                    sticker: false
                },
                icon: false,
                styling: 'bootstrap3'
            });
        },
        updateCounts: function () {
            var that = this;

            $.ajax({
                url: this.settings.getCountUrl,
                dataType: 'json',
                success: function (data) {
                    var reminderCounts = $('.reminder-counts');

                    if (data.preventUpdates) {
                        clearInterval(that.intervalId);
                    }

                    if (data.count > 0) {
                        if (reminderCounts.hasClass('hidden')) {
                            reminderCounts.removeClass('hidden');
                        }
                        reminderCounts.text(data.count);
                    } else {
                        reminderCounts.addClass('hidden');
                    }
                }
            });
        },
        clientUtcOffset: function () {
            return moment().utcOffset(); // minutes
        },
        updateGridColumnListener: function () {
            var _this = this, gridTable = $('#bulk-reminder-search table');
            if (gridTable.length) {
                $(document).on('ready pjax:end', gridTable, function () {
                    _this.updateGridColumn();
                });
            } else {
                _this.updateGridColumn();
            }
        },
        updateGridColumn: function () {
            var elem = $('.reminder-next-time-modify');
            if (elem.length) {
                $(elem).filter(function () {
                    var gridCell = $(this);
                    gridCell.text(moment.utc(gridCell.text()).local().format('DD.MM.YY, HH:mm'))
                });
            }
        }
    };

    $.fn[pluginName] = function (options) {
        this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
        return this;
    };
})(jQuery, window, document);
