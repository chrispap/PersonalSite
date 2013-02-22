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

$(document).ready(function() {
        var imageLinks = $(".entry"); //$('a[href$=".png"], a[href$=".jpg"], a[href$=".gif"], a[href$=".bmp"]');
        if (imageLinks.children('img').length) {
            imageLinks.children('img').each(function() {
                var currentTitle = $(this).attr('title');
                $(this).attr(/*'title', currentTitle + */ ' (click to enlarge image)');
            });
            imageLinks.click(function(e) {
                e.preventDefault();
                $(this).children('img').toggleClass('expanded');
            });
        }
    });

