describe('Login page', () => {
  it('should render the login component', () => {
    cy.server({ force404: true });
    cy.route({
      method: 'POST',
      url: 'http://0.0.0.0:3333/sessions',
      response: [
        {
          statusCode: 404,
          error: {
            type: 'RESOURCE_NOT_FOUND',
            description: 'Wrong password, try again.',
          },
        },
      ],
    });
    cy.visit('/');
    cy.contains('Login');
  });
});
