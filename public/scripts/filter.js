document.addEventListener("DOMContentLoaded", function () {
    let state = 'down'
    let button = document.getElementById('filter_button')
    let image = document.getElementById('filter_arrow')

    button.addEventListener("click", () => {
        if (state === 'right') {
            image.src = "/images/arrow_down.png"
            state = 'down'
        } else {
            image.src = "/images/arrow_right.png"
            state = 'right'
        }
    })
}, false);