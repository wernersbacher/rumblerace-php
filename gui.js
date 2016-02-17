/* global moment */
function formatTime(dur) {
    return dur + "s";
}

function setTuneData(part, preis, worst, best, dur) {
    $("#" + part).find(".tuneCost").html(preis);
    $("#" + part).find(".tuneDur").html(formatTime(dur));
    $("#" + part).find(".tuneMin").html(worst);
    $("#" + part).find(".tuneMax").html(best);

}

function startCountdown() {
    //Show countdown for parts
    var selector = "#tuner, #running_races";
    
    $(selector).find(".tuneProgress").each(function () {
        var duration = $(this).data("timeDuration");
        var time_to_end = $(this).data("timeToend");
        var time_went = duration - time_to_end;
        var id = $(this).attr("id");

        function clearProgress(id) {
            $("#"+id).find(".tuneProgress").remove();
            $("#"+id).find(".tableTopButton").prop("disabled", false);
            //console.log($("#"+id), "#"+id);
            $("#"+id).closest(".removeThis").remove();
        }

        function countdown(id) {
            time_to_end--;
            time_went++;
            
            var width = 0;

            if (time_went > 0)
                width = (100 * time_went) / duration;
            else
                width = 0;
            if (width > 100)
                width = 100;
            
            $("#"+id).find(".tuneProgressBar").css("width", width + "%");
            $("#"+id).find(".tuneProgressText").html(time_to_end + 1 + "s left");
            if (time_to_end >= 0) {
                setTimeout(function(){ countdown(id);}, 1000);
            } else {
                clearProgress(id);
            }
        }
        countdown(id);
    });
}

function setToggle() {
        
        $(".sys").toggle(!(!!Cookies.get("toggle-state")) || Cookies.get("toggle-state") === 'true');
        

        $('#toggle_sys').on('click', function () {
            $(".sys").toggle();
            Cookies.set("toggle-state", $(".sys").first().is(':visible'), {expires: 7, path: '/'});
        });
    }

var currentForm;

$(document).ready(function () {

    //Make tables clickable
    $("table:not(.noclick)").find("th").click(function () {
        $(this).closest("table").find("tr:not(:first)").not(".selling").toggle();
    });

    //Starten des Countdowns für die Tuningteile
    startCountdown();

    //Login div
    $("#login_prev").backstretch("img/brett.jpg");

    //Tooglen der Systemnachrichten
    setToggle();
    //Abfrage der Dialoge
    $(document).on("click", ".dialog", function (e) {
        currentForm = $(this).closest('form');
        e.preventDefault();
        $('<div id="dlg"></div>').dialog({
            modal: true,
            title: "Confirmation",
            open: function () {
                var markup = 'Are you sure?';
                $(this).html(markup);
            },
            buttons: {
                'Sure': function () {
                    currentForm.submit();
                },
                'Nope': function() {
                    $(this).dialog('close');
                }
            }
        }); 

    });
    

});

