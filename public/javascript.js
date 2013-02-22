function about()
{
    alert("chrispapapaulou@gmail.com");
}

function clearText(textfield, defval)
{
    if (textfield.value == defval ) {
                $(textfield).val("");
    }

}

function restoreText(textfield, defval)
{
    if (textfield.value == "" ) {
        $(textfield).val(defval);
    }

}

function panelActivated ( event, ui )
{
    try {
        active = $("#accordion" ).accordion( "option", "active" );
        text = $("#accordion h3").eq(active).text();
        newUrl = PATH + "links/" + text.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        window.history.pushState("","",newUrl);
    } catch (exc) {
    }
}
