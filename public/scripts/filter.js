document.addEventListener("DOMContentLoaded", function () {
    let state = 'right'
    let button = document.getElementById('filter_button')
    let image = document.getElementById('filter_arrow')

    button.addEventListener("click", () => {
        if (state === 'right') {
            console.log('deviens down')
            image.src = "/images/arrow_down.png"
            state = 'down'
        } else {
            console.log('deviens right')
            image.src = "/images/arrow_right.png"
            state = 'right'
        }
    })
}, false);