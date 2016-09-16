/* global moment */
function formatTime(dur) {
    return date("H:i:s", dur - 3600) + "s";
}

function precise_round(num, decimals) {
    return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
}


function setTuneData(part, preis, worst, best, dur) {
    $("#" + part).find(".tuneCost").html(preis);
    $("#" + part).find(".tuneDur").html(formatTime(dur));
    $("#" + part).find(".tuneMin").html(worst);
    $("#" + part).find(".tuneMax").html(best);

}

function startSprit() {
    var max = parseFloat($("#spritTags").data("spritmax"));
    var spm = parseFloat($("#spritTags").data("promin"));
    var sps = spm / 60;
    var old = parseFloat($("#playerSprit").html());

    function setSprit(x) {
        $("#playerSprit").html(precise_round(x, 2));
    }

    function interval() {
        old += sps;
        if (old >= max) {
            old = max;
            setSprit(old);
            clearInterval(refresh);
            $("#spritTags").css('color', '#B50000');
        }
        setSprit(old);
    }
    interval();
    var refresh = setInterval(interval, 1000);
}

function startCountdown() {
    //Show countdown for parts and running races
    var selector = "#tuner, #running_races";

    $(selector).find(".tuneProgress").each(function () {
        var duration = $(this).data("timeDuration");
        var time_to_end = $(this).data("timeToend");
        var time_went = duration - time_to_end;
        var id = $(this).attr("id");

        function clearProgress(id) {
            $("#" + id).closest(".removeThis").remove();
            console.log("#" + id);
            $("#" + id).remove();
            $("#tuner").find(".tableTopButton").prop("disabled", false);
            //console.log($("#"+id), "#"+id);
            $("#" + id).closest(".removeThis").remove();
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

            $("#" + id).find(".tuneProgressBar").css("width", width + "%");
            $("#" + id).find(".tuneProgressText").html(formatTime(time_to_end + 1) + " left");
            if (time_to_end >= 0) {
                setTimeout(function () {
                    countdown(id);
                }, 1000);
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

    //Start des Spritzählers
    startSprit();

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
                'Nope': function () {
                    $(this).dialog('close');
                }
            }
        });

    });

    //Support und Bugreport Dialoge

    $(function () {
        $("#supportus").dialog({
            autoOpen: false,
            show: {effect: "blind", duration: 800}
        });
        $("#bugrep").dialog({
            autoOpen: false
        });
    });

    $(".infoPop").click(function () {
        var id = $(this).data("open");
        $("#" + id).dialog("open");
    });

    //bugreport Formular
    $('#bugForm').ajaxForm(function (responseText) {
        console.log(responseText);
        $("#bugForm").html("Thank you for your feedback!");
    });


    //Change de drivers name

    $('#driverNameChange').click(function () {
        $("#driverName").hide();
        $('#driverNameInput').show().find(".focusThis").focus();
    });
    
    //Sprit autoscroller (dont want to use ajax)
    
    $(".saveScroll").click(function() {
        
        localStorage.setItem("scrollTop", $(window).scrollTop());
        //alert($(window).scrollTop());
    });
    
    if($("#produce").length) {
        //Scroll to saved position
        $(window).scrollTop(localStorage.getItem("scrollTop"));
    } else {
        //if another page gets opened in between
        localStorage.setItem("scrollTop", 0);
    }

});

