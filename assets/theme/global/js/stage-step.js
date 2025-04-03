
(function () {
    "use strict"

    const steps = document.querySelectorAll(".step-item");
    const stepContents = document.querySelectorAll(".step-content-item");
    const nextButtons = document.querySelectorAll(".step-next-btn");
    const prevButtons = document.querySelectorAll(".step-back-btn");
    let active = 0; 

    nextButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            active++;

            if (active >= steps.length) { 
                active = steps.length - 1;
            }
            updateProgress();
        });
    });

    prevButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            active--;
            if (active < 0) { 
                active = 0;
            }
            updateProgress();
        });
    });

    const updateProgress = () => {

        const previousActiveStep = steps[active + 1];
        if (previousActiveStep) {
            previousActiveStep.classList.remove('activated');
        }

        steps.forEach((step, i) => {
            if (i === active) {
                step.classList.add('active', 'activated');
                stepContents[i].classList.add('active');
            } else {
                step.classList.remove('active');
                stepContents[i].classList.remove('active');
            }
        });

        prevButtons.forEach(btn => {
            btn.disabled = active === 0;
        });
        nextButtons.forEach(btn => {
            btn.disabled = active === steps.length - 1;
        });
    };

    updateProgress();

}())