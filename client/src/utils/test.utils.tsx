import { render, RenderResult } from '@testing-library/react';
import React from 'react';
import { MemoryRouter } from 'react-router-dom';

const renderWithRouter = (component: JSX.Element, initialRoute = '/'): RenderResult => {
  return render(<MemoryRouter initialEntries={[initialRoute]}>{component}</MemoryRouter>);
};

/* ---------- MOCKS ----------- */
const sessionsResponseMock = {
  post: {
    success: {
      status: 200,
      data: {
        token: 'asdfasdfasdfasdfasdfasdfasdf',
      },
    },
    failure: {
      status: 404,
      data: {
        error: {
          type: 'RESOURCE_NOT_FOUND',
          description: 'Wrong password, try again.',
        },
      },
    },
  },
};

const postsResponseMock = {
  get: {
    success: {
      status: 200,
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
    },
  },
};

export { renderWithRouter, sessionsResponseMock, postsResponseMock };
