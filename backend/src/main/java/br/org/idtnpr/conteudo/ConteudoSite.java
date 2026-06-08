package br.org.idtnpr.conteudo;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.PreUpdate;
import jakarta.persistence.Table;

import java.time.LocalDateTime;

/**
 * Conteúdo textual editável do site (registro único, sempre id = 1).
 * Espelha os campos editados na aba "Textos" do painel admin.
 */
@Entity
@Table(name = "conteudo_site")
public class ConteudoSite {

    public static final long ID_FIXO = 1L;

    @Id
    private Long id = ID_FIXO;

    @Column(name = "hero_title")
    private String heroTitle;
    @Column(name = "hero_highlight")
    private String heroHighlight;
    @Column(name = "hero_desc")
    private String heroDesc;
    @Column(name = "hero_btn")
    private String heroBtn;

    @Column(name = "about_text")
    private String aboutText;

    @Column(name = "feat1_title")
    private String feat1Title;
    @Column(name = "feat1_desc")
    private String feat1Desc;
    @Column(name = "feat2_title")
    private String feat2Title;
    @Column(name = "feat2_desc")
    private String feat2Desc;
    @Column(name = "feat3_title")
    private String feat3Title;
    @Column(name = "feat3_desc")
    private String feat3Desc;
    @Column(name = "feat4_title")
    private String feat4Title;
    @Column(name = "feat4_desc")
    private String feat4Desc;

    @Column(name = "cta_title")
    private String ctaTitle;
    @Column(name = "cta_desc")
    private String ctaDesc;
    @Column(name = "cta_btn")
    private String ctaBtn;

    @Column(name = "contato_email")
    private String contatoEmail;
    @Column(name = "contato_telefone")
    private String contatoTelefone;

    @Column(name = "logo_url")
    private String logoUrl;
    @Column(name = "hero_img_url")
    private String heroImgUrl;

    @Column(name = "atualizado_em", nullable = false)
    private LocalDateTime atualizadoEm = LocalDateTime.now();

    protected ConteudoSite() {
    }

    @PreUpdate
    void aoAtualizar() {
        this.atualizadoEm = LocalDateTime.now();
    }

    public Long getId() {
        return id;
    }

    public String getHeroTitle() {
        return heroTitle;
    }

    public void setHeroTitle(String heroTitle) {
        this.heroTitle = heroTitle;
    }

    public String getHeroHighlight() {
        return heroHighlight;
    }

    public void setHeroHighlight(String heroHighlight) {
        this.heroHighlight = heroHighlight;
    }

    public String getHeroDesc() {
        return heroDesc;
    }

    public void setHeroDesc(String heroDesc) {
        this.heroDesc = heroDesc;
    }

    public String getHeroBtn() {
        return heroBtn;
    }

    public void setHeroBtn(String heroBtn) {
        this.heroBtn = heroBtn;
    }

    public String getAboutText() {
        return aboutText;
    }

    public void setAboutText(String aboutText) {
        this.aboutText = aboutText;
    }

    public String getFeat1Title() {
        return feat1Title;
    }

    public void setFeat1Title(String feat1Title) {
        this.feat1Title = feat1Title;
    }

    public String getFeat1Desc() {
        return feat1Desc;
    }

    public void setFeat1Desc(String feat1Desc) {
        this.feat1Desc = feat1Desc;
    }

    public String getFeat2Title() {
        return feat2Title;
    }

    public void setFeat2Title(String feat2Title) {
        this.feat2Title = feat2Title;
    }

    public String getFeat2Desc() {
        return feat2Desc;
    }

    public void setFeat2Desc(String feat2Desc) {
        this.feat2Desc = feat2Desc;
    }

    public String getFeat3Title() {
        return feat3Title;
    }

    public void setFeat3Title(String feat3Title) {
        this.feat3Title = feat3Title;
    }

    public String getFeat3Desc() {
        return feat3Desc;
    }

    public void setFeat3Desc(String feat3Desc) {
        this.feat3Desc = feat3Desc;
    }

    public String getFeat4Title() {
        return feat4Title;
    }

    public void setFeat4Title(String feat4Title) {
        this.feat4Title = feat4Title;
    }

    public String getFeat4Desc() {
        return feat4Desc;
    }

    public void setFeat4Desc(String feat4Desc) {
        this.feat4Desc = feat4Desc;
    }

    public String getCtaTitle() {
        return ctaTitle;
    }

    public void setCtaTitle(String ctaTitle) {
        this.ctaTitle = ctaTitle;
    }

    public String getCtaDesc() {
        return ctaDesc;
    }

    public void setCtaDesc(String ctaDesc) {
        this.ctaDesc = ctaDesc;
    }

    public String getCtaBtn() {
        return ctaBtn;
    }

    public void setCtaBtn(String ctaBtn) {
        this.ctaBtn = ctaBtn;
    }

    public String getContatoEmail() {
        return contatoEmail;
    }

    public void setContatoEmail(String contatoEmail) {
        this.contatoEmail = contatoEmail;
    }

    public String getContatoTelefone() {
        return contatoTelefone;
    }

    public void setContatoTelefone(String contatoTelefone) {
        this.contatoTelefone = contatoTelefone;
    }

    public String getLogoUrl() {
        return logoUrl;
    }

    public void setLogoUrl(String logoUrl) {
        this.logoUrl = logoUrl;
    }

    public String getHeroImgUrl() {
        return heroImgUrl;
    }

    public void setHeroImgUrl(String heroImgUrl) {
        this.heroImgUrl = heroImgUrl;
    }
}
