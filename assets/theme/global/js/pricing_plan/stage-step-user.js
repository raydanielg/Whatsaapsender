
(function () {
    "use strict"

    const stepsUser = document.querySelectorAll(".step-item-user");
    const stepContentsUser = document.querySelectorAll(".step-content-item-user");
    const nextButtonsUser = document.querySelectorAll(".step-next-btn-user");
    const prevButtonsUser = document.querySelectorAll(".step-back-btn-user");
    let active = 0; 

    nextButtonsUser.forEach(btnUser => {
        btnUser.addEventListener("click", () => {
            active++;

            if (active >= stepsUser.length) { 
                active = stepsUser.length - 1;
            }
            updateProgressUser();
        });
    });

    prevButtonsUser.forEach(btnUser => {
        btnUser.addEventListener("click", () => {
            active--;
            if (active < 0) { 
                active = 0;
            }
            updateProgressUser();
        });
    });

    const updateProgressUser = () => {

        const previousActiveStep = stepsUser[active + 1];
        if (previousActiveStep) {
            previousActiveStep.classList.remove('activated');
        }

        stepsUser.forEach((stepUser, i) => {
            if (i === active) {
                stepUser.classList.add('active', 'activated');
                stepContentsUser[i].classList.add('active');
            } else {
                stepUser.classList.remove('active');
                stepContentsUser[i].classList.remove('active');
            }
        });

        prevButtonsUser.forEach(btnUser => {
            btnUser.disabled = active === 0;
        });
        nextButtonsUser.forEach(btnUser => {
            btnUser.disabled = active === stepsUser.length - 1;
        });
    };

    updateProgressUser();

}())