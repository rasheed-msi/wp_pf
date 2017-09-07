Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

Array.prototype.collectiveRemove = function (key, value) {
    var keep = [];
    this.forEach(function (item, index, object) {
        if (item[key] != value) {
            keep.push(item)
        }
    });
    return keep;
};