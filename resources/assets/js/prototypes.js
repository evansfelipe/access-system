String.prototype.matches = function(other) { 
    return this.toUpperCase().includes(other.toUpperCase())
};