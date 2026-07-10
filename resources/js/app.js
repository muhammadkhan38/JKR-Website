document.addEventListener('DOMContentLoaded', () => {
    const scheduleTabs = document.querySelectorAll('[data-schedule-tab]');
    const schedulePanels = document.querySelectorAll('[data-schedule-panel]');

    scheduleTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const selectedPanel = tab.dataset.scheduleTab;

            scheduleTabs.forEach((item) => item.setAttribute('aria-selected', String(item === tab)));
            schedulePanels.forEach((panel) => {
                panel.hidden = panel.dataset.schedulePanel !== selectedPanel;
            });
        });
    });

    document.querySelectorAll('[data-live-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const isPlaying = button.getAttribute('aria-pressed') === 'true';
            const nextState = ! isPlaying;
            const icon = button.querySelector('[data-live-icon]');
            const equalizer = document.querySelector('[data-equalizer]');

            button.setAttribute('aria-pressed', String(nextState));
            button.setAttribute('aria-label', nextState ? button.dataset.pauseLabel : button.dataset.startLabel);

            if (icon) {
                icon.textContent = nextState ? '❚❚' : '▶';
            }

            if (equalizer) {
                equalizer.classList.toggle('is-paused', ! nextState);
            }
        });
    });
});
