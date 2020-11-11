describe('Home page', () => {
  it('should render the homepage component', () => {
    const token =
      'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MDUxMzQyNDQsImlzcyI6Imluc3RhZ3JhbS5jbG9uZSIsImlhdCI6MTYwNTA0Nzg0NH0.Rg1iGzoCiAl14BPUJQYjm7n941WNlYBmOqGsaruRPBo';
    cy.server({ force404: true });
    cy.route({
      method: 'POST',
      url: 'http://0.0.0.0:3333/sessions',
      response: [
        {
          statusCode: 200,
          data: {
            token: token,
          },
        },
      ],
    });
    cy.visit('/');
    cy.contains('Homepage');
  });
});
