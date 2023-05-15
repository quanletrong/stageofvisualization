let bsize_class_img = {
    '1' : '468x90',
    '2' : '300x250',
    '3' : '300x600',
    '4' : '980x90',
    '5' : '160x600',
    '6' : '728x90',
    '7' : 'cpcbidding',
    '8' : 'sponsorads',
    '9' : 'kingsize1160x250',
    '18' : 'kingsizearticle600x300',
    '19' : 'kingsizearticle600x300',
    '20' : 'kingsizearticle600x300',
    '21' : 'kingsize1160x250',
}

//check camp budget
function cad_st1_camp_budget_change() {
    var minBudget = $('#hddMinCampBudget').val();
    minBudget = parseInt(minBudget);

    var campBudget = $('#txtCampBudget').val();
    campBudget = campBudget.replace(/,/gi,'');
    campBudget = parseFloat(campBudget);
    $('#txtCampBudget').val(addCommas(campBudget));

    if(campBudget < minBudget)
    {
        $('#errorCreateCampBudget').removeClass('d-none');
        STATE.campaign.maxValueDay = '0';
    }
    else
    {
        $('#errorCreateCampBudget').addClass('d-none');
        $('#txtCampBudget').removeClass('error-border');
        STATE.campaign.maxValueDay = addCommas(campBudget);
    }
}

//check time newad camp
function cad_st1_camp_time_change() {
    var arr = ($('#campStartDate').val()).split('-');
    var startDate = new Date(arr[2] + '/' + arr[1] + '/' + arr[0]);

    var arr_end = ($('#campEndDate').val()).split('-');
    var endDate = new Date(arr_end[2] + '/' + arr_end[1] + '/' + arr_end[0]);

    if (startDate <= endDate) {
        var check = check_show_time($('#campStartTime').val(), $('#campEndTime').val());
        var check_t = check_time($('#campStartTime').val(), $('#campEndTime').val());
        if (check || check_t) {
            $('#errorCreateCampTime2').addClass('d-none');
        } else {
            $('#errorCreateCampTime2').removeClass('d-none');
        }
    } else {
        $('#errorCreateCampTime').removeClass('d-none');
    }
}

function check_time(st,et){
    var check = true;
    var d = new Date();
    var h = d.getHours();
    var m = d.getMinutes();

    var starttime = (st).split(':');
    var endtime = (et).split(':');
    if(et != "" && st != "")
    {
        if(starttime[0] < h || endtime[0] < h)
        {
            check = false;
        }
        else if(starttime[0] == h)
        {
            check = (starttime[1] < m) ? false : true;
            if(endtime[0] == h)
            {
                check = (starttime[1] < m || endtime[1] <= m) ? false : true;
            }
        }
        else
        {
            check = true;
        }
    }
    else if(et == "" && st != "")
    {
        if(starttime[0] < h)
        {
            check = false;
        }
        else if(starttime[0] == h)
        {
            check = (starttime[1] < m) ? false : true;
        }
        else
        {
            check = true;
        }
    }
    else if(et != "" && st == "")
    {
        if(endtime[0] < h)
        {
            check = false;
        }
        else if(endtime[0] == h)
        {
            check = (endtime[1] <= m) ? false : true;
        }
        else
        {
            check = true;
        }
    }
    return check;
}

function check_show_time(st,et){
    var check = true;
    // if (!$('#chkRunToday').prop('checked')) {
        var starttime = (st).split(':');
        var endtime = (et).split(':');
        if(starttime[0] == endtime[0]){
            if(starttime[1] >= endtime[1]){
                check = false;
            }else{
                check = true;
            }
        }else if(starttime[0] > endtime[0]){
            check = false;
        }else{
            check = true;
        }
    // }
    return check
}

function render_script_banner(width, height, desc_url, script, type = 3, cpa = 1) {
    let banner_id = Math.floor(Math.random() * 500) + 1; // random 1->500
    let campaign_id = Math.floor(Math.random() * 500) + 1; // random 1->500
    script = $.trim(script);
    if (script == '')
    {
        return '';
    }

    let type_script = type == '5' ? 'html' : 'iframe';

    desc_url = desc_url.replace("/", "\\/");
    desc_url = desc_url.replace("'", "\\'");
    desc_url = desc_url.replace('"', '\\"');

    script = script.replace("\r", "");
    script = script.replace("\n", "");
    script = script.replace("\n", "");
    script = $.trim(script.replace('/\s\s+/', ' '));
    script = script.replace("/", "\\/");
    script = script.replace("'", "\\'");
    script = script.replace('"', '\\"');

    let html = `<div id="adnzone_${banner_id}"></div>`;
    html += `
        <script type="text/javascript">
            var __adnZone${banner_id}chk = false;
            var adnZone_${banner_id}Data = {
                "type": "7k",
                "size": "${width}x${height}",
                "adn": true,
                "is_db": 0,
                "ext": {
                    "logo": 0
                },
                "df": [{
                    "src_bk": "",
                    "width": ${width},
                    "link": "${removeAllHtmlTag(desc_url)}",
                    "is_default": 1,
                    "l": "",
                    "type": "${type_script}",
                    "cid": ${campaign_id},
                    "title": "",
                    "link3rd": "",
                    "tablet": 0,
                    "height": ${height},
                    "link_views": "",
                    "r": 1,
                    "isview": "1",
                    "src_exp": "",
                    "cpa": ${cpa},
                    "src": \'${script}\',
                    "bid": ${banner_id}
                }],
                "lst": []
            };
            window.adnzone${banner_id} = new cpmzone(${banner_id});
            adnzone${banner_id}.show(adnZone_${banner_id}Data);
        </script>
    `;
    return html;
}


function checkshowtime(st,et){
    var check = true;
    var starttime = (st).split(' : ');
    var endtime = (et).split(' : ');
    if(starttime[0] == endtime[0]){
        if(starttime[1] >= endtime[1]){
            check = false;
        }else{
            check = true;
        }
    }else if(starttime[0] > endtime[0]){
        check = false;
    }else{
        check = true;
    }
    return check
}