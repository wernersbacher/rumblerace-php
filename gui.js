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
    $("#tuner").find(".tuneProgress").each(function () {
        var duration = $(this).data("timeDuration");
        var time_to_end = $(this).data("timeToend");
        var time_went = duration - time_to_end;

        function clearProgress() {
            $("#tuner").find(".tuneProgress").remove();
            $("#tuner").find(".tableTopButton").prop("disabled", false);

        }

        function countdown() {
            time_to_end--;
            time_went++;
            var width = 0;

            if (time_went > 0)
                width = (100 * time_went) / duration;
            else
                width = 0;
            if (width > 100)
                width = 100;

            $("#tuner").find(".tuneProgressBar").css("width", width + "%");
            $("#tuner").find(".tuneProgressText").html(time_to_end+1 + "s left");
            if (time_to_end >= 0) {
                setTimeout(countdown, 1000);
            } else {
                clearProgress();
            }
        }
        countdown();


    });


}

$(document).ready(function () {

    //Make tables clickable
    $("table:not(.noclick)").find("th").click(function () {
        $(this).closest("table").find("tr:not(:first)").not(".selling").toggle();
    });

    startCountdown();
    
    $("#login_prev").backstretch("img/brett.jpg");
   
    
    

});
