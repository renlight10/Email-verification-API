verify email address before sending email for next level email verification with possible method
- [x] disposable check
- [x] trusted domain check
- [x] smtp check
- [ ]  ~~mx check~~ (commented /enough with smtp check)
- [ ] blacklist email address
- [ ] admin ui for manage list etc

usage : http://host/folder/u/*email address*

demo : http://lab-microservers.rhcloud.com/email/u/ren_ice@live.com

## scoring works

- [x] when smtp check passed
- [x] when domain not listed on disposable
- [x] when domain listed on trusted

### external source

1. disposable list https://github.com/martenson/disposable-email-domains
2. smtp validate class https://github.com/zytzagoo/smtp-validate-email