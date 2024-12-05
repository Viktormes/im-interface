import dayjs from "dayjs";

class Presenter {
    person = null;

    constructor(person = null) {
        this.person = person;
    }

    get name() {
        const officialName = this.person.name.filter(n => n.use === 'official')?.[0];
        return officialName.given.join(' ') + ' ' + officialName.family;
    }

    get firstName() {
        const officialName = this.person.name.filter(n => n.use === 'official')?.[0];
        return officialName.given.join(' ');
    }

    get hsaId() {
        return this.person.identifier.filter(n => n.system === 'urn:oid:1.2.752.29.6.2.1')?.[0]?.value || '-';
    }

    get ssn() {
        return this.person.identifier.filter(n => n.system === 'urn:oid:1.2.752.29.4.13')?.[0]?.value || '-';
    }

    get lastObservation() {
        if (!this.person.last_observation) return '-';
        return dayjs(this.person.last_observation).format('YYYY-MM-DD HH:mm');
    }

    get gp() {
        return this.person.generalPractitioner?.[0]?.display || '-';
    }

    get diagnosis() {
        return this.person.diagnoses?.[0]?.display || '-';
    }

    for(person) {
        this.person = person;
        return this;
    }
}

const presenter = new Presenter();

export function usePresenter() {
    return function (person) {
        return presenter.for(person);
    };
}
