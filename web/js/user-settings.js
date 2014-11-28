function updateSettings() {

    var audio = new Array();
    var video = new Array();
    var film = new Array();
    var i = 0;
    $('#sortableAudio tbody tr').each(function(index, id)
    {
        columnAnchorID = $(this).attr('data-sort');
        title = $(this).attr('data-title');
        is_required = $(this).attr('data-is_required');
        hide = $(this).attr('data-hide_it');
        audio[i] = {
            title: title,
            field: columnAnchorID,
            hidden: hide,
            is_required: is_required
        };
        i++;
    });
    i = 0;
    $('#sortableVideo tbody tr').each(function(index, id)
    {
        columnAnchorID = $(this).attr('data-sort');
        title = $(this).attr('data-title');
        is_required = $(this).attr('data-is_required');
        hide = $(this).attr('data-hide_it');
        video[i] = {
            title: title,
            field: columnAnchorID,
            hidden: hide,
            is_required: is_required
        };
        i++;

    });
    i = 0;
    $('#sortableFilm tbody tr').each(function(index, id)
    {
        columnAnchorID = $(this).attr('data-sort');
        title = $(this).attr('data-title');
        is_required = $(this).attr('data-is_required');
        hide = $(this).attr('data-hide_it');
        film[i] = {
            title: title,
            field: columnAnchorID,
            hidden: hide,
            is_required: is_required
        };
        i++;

    });
    var userSettings = ['audio', 'video', 'film'];
    userSettings = {audio: audio, video: video, film: film};
    $.ajax({
        type: 'POST',
        url: site_url + '/fieldsettings/update',
        async: false,
        data: {
            settings: userSettings
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.reload) {
                window.location.reload();
            }
        }
    });
//    console.log(userSettings);
}

function hideIt(obj) {
    if (obj.checked) {
        $('#' + obj.id).parent().parent().attr('data-hide_it', 1);
    } else {
        $('#' + obj.id).parent().parent().attr('data-hide_it', 0);
    }
}

function saveEnableBackup()
{
    if ($('#enable').is(':checked'))
    {
        console.log('yupee');
    }
    else {
        console.log('done done done :) :P');
    }
}
