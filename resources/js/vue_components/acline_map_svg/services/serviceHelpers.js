// хелперы разные

// ------------------------------------------------------------------
// первая заглавная буква
export const serviceCFirst = (getString) => {

    // возвращаемый параметр
    return getString.charAt(0).toUpperCase() + getString.slice(1);
}
