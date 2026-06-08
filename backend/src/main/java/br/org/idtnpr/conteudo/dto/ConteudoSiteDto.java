package br.org.idtnpr.conteudo.dto;

import br.org.idtnpr.conteudo.ConteudoSite;

/**
 * DTO bidirecional do conteúdo textual do site.
 * Usado tanto na leitura (GET público) quanto na escrita (PUT admin).
 */
public record ConteudoSiteDto(
        String heroTitle,
        String heroHighlight,
        String heroDesc,
        String heroBtn,
        String aboutText,
        String feat1Title,
        String feat1Desc,
        String feat2Title,
        String feat2Desc,
        String feat3Title,
        String feat3Desc,
        String feat4Title,
        String feat4Desc,
        String ctaTitle,
        String ctaDesc,
        String ctaBtn,
        String contatoEmail,
        String contatoTelefone,
        String logoUrl,
        String heroImgUrl
) {

    public static ConteudoSiteDto de(ConteudoSite c) {
        return new ConteudoSiteDto(
                c.getHeroTitle(), c.getHeroHighlight(), c.getHeroDesc(), c.getHeroBtn(),
                c.getAboutText(),
                c.getFeat1Title(), c.getFeat1Desc(), c.getFeat2Title(), c.getFeat2Desc(),
                c.getFeat3Title(), c.getFeat3Desc(), c.getFeat4Title(), c.getFeat4Desc(),
                c.getCtaTitle(), c.getCtaDesc(), c.getCtaBtn(),
                c.getContatoEmail(), c.getContatoTelefone(),
                c.getLogoUrl(), c.getHeroImgUrl()
        );
    }

    /** Aplica os valores deste DTO na entidade (usado no update do admin). */
    public void aplicarEm(ConteudoSite c) {
        c.setHeroTitle(heroTitle);
        c.setHeroHighlight(heroHighlight);
        c.setHeroDesc(heroDesc);
        c.setHeroBtn(heroBtn);
        c.setAboutText(aboutText);
        c.setFeat1Title(feat1Title);
        c.setFeat1Desc(feat1Desc);
        c.setFeat2Title(feat2Title);
        c.setFeat2Desc(feat2Desc);
        c.setFeat3Title(feat3Title);
        c.setFeat3Desc(feat3Desc);
        c.setFeat4Title(feat4Title);
        c.setFeat4Desc(feat4Desc);
        c.setCtaTitle(ctaTitle);
        c.setCtaDesc(ctaDesc);
        c.setCtaBtn(ctaBtn);
        c.setContatoEmail(contatoEmail);
        c.setContatoTelefone(contatoTelefone);
        c.setLogoUrl(logoUrl);
        c.setHeroImgUrl(heroImgUrl);
    }
}
