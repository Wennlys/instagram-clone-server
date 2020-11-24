import { render, RenderResult } from '@testing-library/react';
import React from 'react';
import { MemoryRouter } from 'react-router-dom';

const renderWithRouter = (component: JSX.Element, initialRoute = '/'): RenderResult => {
  return render(<MemoryRouter initialEntries={[initialRoute]}>{component}</MemoryRouter>);
};

/* ---------- MOCKS ----------- */
const sessionsResponseMock = {
  post: {
    success: Promise.resolve({
      data: [
        {
          statusCode: 200,
          token: 'asdfasdfasdfasdfasdfasdfasdf',
        },
      ],
    }),
    failure: Promise.resolve({
      data: [
        {
          statusCode: 404,
          error: {
            type: 'RESOURCE_NOT_FOUND',
            description: 'Wrong password, try again.',
          },
        },
      ],
    }),
  },
};

const postsResponseMock = {
  get: {
    success: Promise.resolve({
      data: [
        {
          id: 1,
          image: 'http://localhost:3333/tmp/avatar.jpg',
          description: 'Nothing to see here. :P',
          userName: 'user1',
        },
        {
          id: 2,
          image: 'http://localhost:3333/tmp/avatar.jpg',
          description: 'Nothing to see here. :P',
          userName: 'user2',
        },
      ],
    }),
  },
};

export { renderWithRouter, sessionsResponseMock, postsResponseMock };
