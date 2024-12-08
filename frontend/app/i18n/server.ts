import { translations } from './translations'

export function getTranslations(lang: string = 'en') {
    return {
        t: (key: string) => {
            const keys = key.split('.')
            let value: any = translations[lang]
            for (const k of keys) {
                value = value[k]
                if (value === undefined) {
                    console.warn(`Translation key not found: ${key}`)
                    return key
                }
            }
            return value
        }
    }
}
