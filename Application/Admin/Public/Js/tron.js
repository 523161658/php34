$(function () {
    $('tr.tron').mouseover(function () {
        $(this).find('td').css('background-color', '#BBDDE5');
    });
    $('tr.tron').mouseout(function () {
        $(this).find('td').css('background-color', '#FFF');
    });
});

