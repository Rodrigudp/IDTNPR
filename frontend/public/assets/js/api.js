(function (global) {
  'use strict';

  var API_ORIGIN = (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')
    ? 'http://localhost:8080'
    : window.location.origin;
  var API_BASE = API_ORIGIN + '/api';
  var TOKEN_KEY = 'idtnpr_admin_token';

  function salvarToken(token) {
    localStorage.setItem(TOKEN_KEY, token);
  }

  function obterToken() {
    return localStorage.getItem(TOKEN_KEY);
  }

  function limparToken() {
    localStorage.removeItem(TOKEN_KEY);
  }

  function estaLogado() {
    return !!obterToken();
  }

  function urlArquivo(url) {
    if (!url) {
      return '';
    }
    if (/^https?:\/\//i.test(url)) {
      return url;
    }
    if (url.charAt(0) === '/') {
      return API_ORIGIN + url;
    }
    return API_BASE + '/' + url;
  }

  async function pedir(metodo, caminho, opcoes) {
    opcoes = opcoes || {};
    var headers = {};
    var config = { method: metodo, headers: headers };

    if (opcoes.corpo !== undefined) {
      headers['Content-Type'] = 'application/json';
      config.body = JSON.stringify(opcoes.corpo);
    } else if (opcoes.formData) {
      config.body = opcoes.formData;
    }

    if (opcoes.autenticado) {
      var token = obterToken();
      if (token) {
        headers['Authorization'] = 'Bearer ' + token;
      }
    }

    var resposta = await fetch(API_BASE + caminho, config);

    if (resposta.status === 204) {
      return null;
    }

    var texto = await resposta.text();
    var dados = null;
    if (texto) {
      try {
        dados = JSON.parse(texto);
      } catch (e) {
        dados = null;
      }
    }

    if (!resposta.ok) {
      var msg = (dados && (dados.mensagem || dados.message)) || ('Erro ' + resposta.status + '.');
      var erro = new Error(msg);
      erro.status = resposta.status;
      erro.dados = dados;
      throw erro;
    }

    return dados;
  }

  global.API = {
    ORIGIN: API_ORIGIN,
    BASE: API_BASE,
    salvarToken: salvarToken,
    obterToken: obterToken,
    limparToken: limparToken,
    estaLogado: estaLogado,
    urlArquivo: urlArquivo,
    get: function (caminho, opcoes) { return pedir('GET', caminho, opcoes); },
    post: function (caminho, opcoes) { return pedir('POST', caminho, opcoes); },
    put: function (caminho, opcoes) { return pedir('PUT', caminho, opcoes); },
    patch: function (caminho, opcoes) { return pedir('PATCH', caminho, opcoes); },
    del: function (caminho, opcoes) { return pedir('DELETE', caminho, opcoes); }
  };
})(window);
