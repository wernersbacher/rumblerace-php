/* global moment */
function formatTime(dur) {
    return date("H:i:s", dur - 3600) + "s";
}

function precise_round(num, decimals) {
    return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
}


function setTuneData(part, preis, dur, acc, speed, hand, dura) {
    $("#" + part).find(".tuneCost").html(preis);
    $("#" + part).find(".tuneDur").html(formatTime(dur));
    $("#" + part).find(".tune_acc").html(acc);
    $("#" + part).find(".tune_speed").html(speed);
    $("#" + part).find(".tune_hand").html(hand);
    $("#" + part).find(".tune_dura").html(dura);

}

function nwc(x) {
    if (x === undefined)
        return "unknown";
    x = precise_round(x, 2);
    var save = $("#langForm").data("lang");
    var komma = ".";
    var tausend = ",";
    if (save === "de") {
        komma = ",";
        tausend = ".";
    }
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, tausend);
    return parts.join(komma);
}

function startSprit() {
    var max = parseFloat($("#spritTags").data("spritmax"));
    var spm = parseFloat($("#spritTags").data("promin"));
    var sps = spm / 60;
    var old = parseFloat($("#playerSprit").html());

    function setSprit(x) {
        $("#playerSprit").html(nwc(x) + " &#8467;");
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

    if (Cookies.get("toggle-state") === 'true') {
        console.log("showing");
    } else {
        console.log("hiding");
        $("#toggle_sys").addClass("offTableTop");
        $(".sys").toggle();
    }

    //$(".sys").toggle( !(!!Cookies.get("toggle-state")) || Cookies.get("toggle-state") === 'true' );


    $('#toggle_sys').on('click', function () {
        console.log("toggle");
        $(".sys").toggle(200);
        $("#toggle_sys").toggleClass("offTableTop");
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
    console.log("test");
    setToggle();

    //Abfrage der Dialoge
    $(document).on("click", ".dialog", function (e) {
        currentForm = $(this).closest('form');
        var mark = "Are you sure?";
        if (currentForm.data("dialog").length > 0)
            mark = currentForm.data("dialog");

        e.preventDefault();
        $('<div id="dlg"></div>').dialog({
            modal: true,
            title: "Confirmation",
            open: function () {

                $(this).html(mark);
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
    $(".saveScroll").click(function () {

        localStorage.setItem("scrollTop", $(window).scrollTop());
        //save current page
        localStorage.setItem("page-id", document.getElementsByTagName("title")[0].innerHTML);
        //alert($(window).scrollTop());
    });

    /*
     * only scroll to saved pos. if on on of those pages AAAAND the site is the same as before
     */
    if (($("#produce").length || $("#racing").length) && localStorage.getItem("page-id") === document.getElementsByTagName("title")[0].innerHTML) {
        //Scroll to saved position
        $(window).scrollTop(localStorage.getItem("scrollTop"));
    } else {
        //if another page gets opened in between
        localStorage.setItem("scrollTop", 0);
    }

    //calc fuel cost/max win
    $("#calcSprit").children(".sp").bind("propertychange change click keyup input paste", function () {
        var price = parseFloat($("#calcSprit").find(".sp_price").val());
        var amount = parseFloat($("#calcSprit").find(".sp_amount").val());
        var res = amount * price;
        if (isNaN(res))
            res = "--";
        else
            res = nwc(res);
        $("#calcSpritResult").html(res);

    });

    //upgrades
    if ($("#nodes").length) {
        var chains = [];
        $('.node').each(function () {
            var chain = $(this).data("chain");
            if (chains.indexOf(chain) < 0)
                chains.push(chain);
        });
        console.log(chains);
        //make connections
        for (var i = 0, length = chains.length; i < length; i++) {
            var chain_id = chains[i];
            var chain_arr = $(".chain_" + chain_id).toArray();

            for (var j = 0, length = chain_arr.length; j < length; j++) {
                if (j + 1 <= chain_arr.length)
                    $(chain_arr[j]).add(chain_arr[j + 1]).connections();
            }

        }


    }

    //Tooltips
    var clicked = false;
    var stayopen = false;
    $('.tooltips').tooltipster({
        theme: 'tooltipster-borderless',
        contentCloning: true,
        trigger: 'custom',
        animationDuration: 50,
        interactive: true,
        minWidth: 100,
        maxWidth: 300
    }).on('mouseover', function () {
        $(this).tooltipster('show');
    }).on('mouseout', function () {
        if (!clicked) { //Nur bei normalen Mouseover..
            $(this).tooltipster('hide');
            /*setTimeout(function () {
             console.log(stayopen);
             
             if(!stayopen) //nur schließen, wenn der User das Item verlassen hat, nicht aber beim Verlassen auf das tooltip
             $("#"+stayopen).tooltipster('hide');
             
             }, 200);*/
        }
    }).on('click', function (e) {
        if (!clicked) {
            clicked = true;
            $(this).tooltipster('show');
        } else {
            clicked = false;
            $(this).tooltipster('hide');
        }
        e.stopPropagation(); // This is the preferred method.
        return false;
    });

    /*$(".tipDiv").on("mouseover", function () {
     stayopen = $(this).data("Id");
     console.log("stayopen");
     }).on("mouseout", function () {
     $("#"+stayopen).tooltipster('hide');
     console.log(stayopen);
     stayopen = false;
     });*/

    $(document).click(function () {
        //if ($(evt.target).attr('class') !== "tooltips")
        $('.tooltips').tooltipster('hide');
    });



});

