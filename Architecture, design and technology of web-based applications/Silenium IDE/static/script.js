/**
 * Created by VADIM on 28-Apr-17.
 */
$(document).ready(function () {
    if ($("body").attr("data-date") !== undefined) {
        var date=$("body").attr("data-date")
        runtests(date,time,often);
    }
});

function runtests(date,time,often) {
    $.ajax({ type: "GET",
             url: "/results",
             success : function(text)
             {
                 var response = text;
                 showresults(response);
             }
    });
}

function validate(date, time, often) {
    if(date.length > 0 && time.length > 0 && often.length > 0) {
        return checktime(date, time)
    } else {
        return false;
    }
}

function showresults(response) {
    var html = "<li><b>Last launching time: " + new Date().toLocaleString() + "</b></li>";
    for(var i = 0; i < response.length; i++) {
        var split1 = response[i].split("-");
        if(split1.length > 70) {
            var split2 = split1[90].split(" ");
        }
    }
    $("#list").html(html);
}