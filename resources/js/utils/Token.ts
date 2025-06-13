import Cookies from 'js-cookie';

const TOKEN_KEY = 'jwt_token';

export function setToken(token: string, expiresDays = 7) {
    Cookies.set(TOKEN_KEY, token, { expires: expiresDays, secure: true, sameSite: 'Strict' });
}

export function getToken(): string | undefined {
    return Cookies.get(TOKEN_KEY);
}

export function deleteToken() {
    Cookies.remove(TOKEN_KEY);
}
