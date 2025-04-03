
(function () {
    "use strict"

    const stepsAdmin = document.querySelectorAll(".step-item-admin");
    const stepContentsAdmin = document.querySelectorAll(".step-content-item-admin");
    const nextButtonsAdmin = document.querySelectorAll(".step-next-btn-admin");
    const prevButtonsAdmin = document.querySelectorAll(".step-back-btn-admin");
    let active = 0; 

    nextButtonsAdmin.forEach(btnAdmin => {
        btnAdmin.addEventListener("click", () => {
            active++;

            if (active >= stepsAdmin.length) { 
                active = stepsAdmin.length - 1;
            }
            updateProgressAdmin();
        });
    });

    prevButtonsAdmin.forEach(btnAdmin => {
        btnAdmin.addEventListener("click", () => {
            active--;
            if (active < 0) { 
                active = 0;
            }
            updateProgressAdmin();
        });
    });

    const updateProgressAdmin = () => {

        const previousActiveStep = stepsAdmin[active + 1];
        if (previousActiveStep) {
            previousActiveStep.classList.remove('activated');
        }

        stepsAdmin.forEach((stepAdmin, i) => {
            if (i === active) {
                stepAdmin.classList.add('active', 'activated');
                stepContentsAdmin[i].classList.add('active');
            } else {
                stepAdmin.classList.remove('active');
                stepContentsAdmin[i].classList.remove('active');
            }
        });

        prevButtonsAdmin.forEach(btnAdmin => {
            btnAdmin.disabled = active === 0;
        });
        nextButtonsAdmin.forEach(btnAdmin => {
            btnAdmin.disabled = active === stepsAdmin.length - 1;
        });
    };

    updateProgressAdmin();

}())