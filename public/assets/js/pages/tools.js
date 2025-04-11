$(document).ready(function () {
    const popupSettings = {
        width: 400,
        height: 200,
        resizable: true,
        isModal: true,
        autoOpen: false,
        modalOpacity: 0.3,
        animationType: 'fade'
    };

    $("#popup1").jqxWindow({ ...popupSettings, title: "Popup 1" });
    $("#popup2").jqxWindow({ ...popupSettings, title: "Popup 2" });
    $("#popup3").jqxWindow({ ...popupSettings, title: "Popup 3" });
    $("#popup4").jqxWindow({ ...popupSettings, title: "Popup 4" });

    $('#openPopup1').on('click', () => $("#popup1").jqxWindow('open'));
    $('#openPopup2').on('click', () => $("#popup2").jqxWindow('open'));
    $('#openPopup3').on('click', () => $("#popup3").jqxWindow('open'));
    $('#openPopup4').on('click', () => $("#popup4").jqxWindow('open'));
});
