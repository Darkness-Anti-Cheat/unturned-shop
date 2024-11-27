try {
    new Sortable.create(document.getElementById('card-container'), {
        handle: ".movable",
        animation: 150,
        dragClass: "drag",
        onEnd: () => {},
        group: "cards",
        store: {
            set:(sortable) => {
                const orden = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, orden.join('|'));
            },
            get: (sortable) => {
                const orden = localStorage.getItem(sortable.options.group.name);
                return orden ? orden.split('|') : [];
            }
        }
    });
} 
catch {
    
}