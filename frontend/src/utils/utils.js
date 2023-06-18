export const utf8_to_b64 = (str) => {
  return btoa(unescape(encodeURIComponent(str)))
}

export const b64_to_utf8 = (str) => {
  return decodeURIComponent(escape(atob(str)))
}

export const setCookie = (key, str, exdays) => {
  const value = JSON.stringify(str)
  const encode = btoa(unescape(encodeURIComponent(value)))
  const d = new Date()
  d.setTime(d.getTime() + exdays * 60 * 1000)
  let expires = 'expires=' + d.toUTCString()
  document.cookie = key + '=' + encode + ';' + expires + ';path=/'
}

export const getCookie = (key) => {
  let name = key + '='
  let ca = document.cookie.split(';')
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i]
    while (c.charAt(0) == ' ') {
      c = c.substring(1)
    }
    if (c.indexOf(name) == 0) {
      const decode = decodeURIComponent(escape(atob(c.substring(name.length, c.length))))
      return JSON.parse(decode)
    }
  }
  return ''
}

export const getRememberMe = () => {
  let encrypted_remember_me = localStorage.getItem('remember_me_kp') || ''
 
  if (!encrypted_remember_me) {
    return { email: '', remember_me: false }
  }

  encrypted_remember_me = JSON.parse(encrypted_remember_me)
  let decrypted_data = JSON.parse(b64_to_utf8(encrypted_remember_me))
 
  return decrypted_data
}

export const deleteCookie = (key) => {
  const d = new Date()
  let expires = 'expires=' + d.toUTCString()
  document.cookie = key + '=' + ';Path=/' + ';' + expires
  // document.cookie = key +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

export const getStayedIn = () => {
  let encryptedData = localStorage.getItem('stayed_in') || ''

  if (!encryptedData) {
    return ''
  }
  encryptedData = JSON.parse(encryptedData)

  return b64_to_utf8(encryptedData)
}

export const getRefreshTokenExpiration = (refresh_token) => {
  const tokenParts = JSON.parse(atob(refresh_token.split('.')[1]))
  const now = Math.ceil(Date.now() / 1000)

  if (tokenParts.exp > now) {
    const cookiesExpiration = tokenParts.exp - now
    let min = Math.floor((cookiesExpiration / 60) << 0)
    min = min + 120
 

    return min
  }
}

export const validEmail = new RegExp('^([a-zA-Z0-9\\-])+\\.([a-zA-Z0-9\\-])+@(tup.edu.ph)$')

export const validPassword = new RegExp(
  '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$'
)
