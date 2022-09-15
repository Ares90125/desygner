const APP_TOKEN_KEY = process.env.REACT_APP_TOKEN_KEY;

export const saveToken = (token) => {
    if (token instanceof Object) {
        token = JSON.stringify(token);
    }
    localStorage.setItem(APP_TOKEN_KEY, token);
};
export const getToken = () => {
    return localStorage.getItem(APP_TOKEN_KEY);
};

export const removeToken = () => {
    localStorage.removeItem(APP_TOKEN_KEY);
};

const LocalStorage = {
    saveToken,
    getToken,
    removeToken,
};

export default LocalStorage;
